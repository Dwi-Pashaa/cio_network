<?php

namespace App\Services;

/**
 * RouterOS API client class
 * Compatible with RouterOS v6 and v7, and PHP 8.1+
 */
class RouterosAPI
{
    public $connected = false;
    public $port = 8728;
    public $ssl = false;
    public $timeout = 4;
    public $attempts = 3;
    public $delay = 1;

    protected $socket;
    public $last_error = '';
    public $debug_logs = [];

    public function connect($ip, $login, $password, $port = null, $ssl = false)
    {
        $this->port = $port ?? ($ssl ? 8729 : 8728);
        $this->ssl = $ssl;
        $this->debug_logs = [];

        $proto = $this->ssl ? 'ssl://' : '';
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);

        $this->socket = @stream_socket_client(
            $proto . $ip . ':' . $this->port,
            $errno,
            $errstr,
            $this->timeout,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$this->socket) {
            $this->last_error = "Gagal membuka socket ke {$ip}:{$this->port}. Error: {$errstr} ({$errno})";
            return false;
        }

        socket_set_timeout($this->socket, $this->timeout);

        // Modern login (v6.43+ & v7)
        $this->write('/login', false);
        $this->write('=name=' . $login, false);
        $this->write('=password=' . $password);
        $response = $this->read(false);

        if (isset($response[0]) && $response[0] === '!done') {
            if (!isset($response[1])) {
                $this->connected = true;
                return true;
            } elseif (str_starts_with($response[1], '=ret=')) {
                $challenge = pack('H*', substr($response[1], 5));
                $hash = md5(chr(0) . $password . $challenge);
                $this->write('/login', false);
                $this->write('=name=' . $login, false);
                $this->write('=response=00' . $hash);
                $resp2 = $this->read(false);
                if (isset($resp2[0]) && $resp2[0] === '!done') {
                    $this->connected = true;
                    return true;
                }
            }
        }

        $this->last_error = "Autentikasi gagal. Username atau password tidak diterima RouterOS.";
        $this->disconnect();
        return false;
    }

    public function disconnect()
    {
        if (is_resource($this->socket)) {
            @fclose($this->socket);
        }
        $this->connected = false;
    }

    public function parseResponse(array $response): array
    {
        $parsed = [];
        $current = [];

        foreach ($response as $line) {
            if ($line === '!re') {
                if (!empty($current)) {
                    $parsed[] = $current;
                    $current = [];
                }
            } elseif ($line === '!done') {
                if (!empty($current)) {
                    $parsed[] = $current;
                }
                break;
            } elseif ($line === '!trap' || $line === '!fatal') {
                continue;
            } else {
                if (str_starts_with($line, '=')) {
                    $parts = explode('=', substr($line, 1), 2);
                    if (count($parts) === 2) {
                        $current[$parts[0]] = $parts[1];
                    }
                }
            }
        }

        return $parsed;
    }

    public function write($param, $param2 = true)
    {
        if ($this->socket === null) return false;

        if (is_array($param)) {
            foreach ($param as $word) $this->writeWord($word);
        } else {
            $this->writeWord($param);
        }

        if ($param2) $this->writeWord('');
        return true;
    }

    public function read($parse = true)
    {
        $response = [];
        while (true) {
            $word = $this->readWord();
            if ($word === '') {
                $status = stream_get_meta_data($this->socket);
                if ($status['timed_out'] || feof($this->socket)) break;
                continue;
            }
            $response[] = $word;
            if ($word === '!done' || $word === '!fatal') break;
        }

        return $parse ? $this->parseResponse($response) : $response;
    }

    protected function writeWord($word)
    {
        $this->encodeLength(strlen($word));
        @fwrite($this->socket, $word);
    }

    protected function readWord(): string
    {
        $byteCount = $this->decodeLength();
        if ($byteCount === 0) return '';

        $word = '';
        while (strlen($word) < $byteCount) {
            $chunk = @fread($this->socket, $byteCount - strlen($word));
            if ($chunk === false || $chunk === '') {
                $status = stream_get_meta_data($this->socket);
                if ($status['timed_out'] || feof($this->socket)) break;
                continue;
            }
            $word .= $chunk;
        }
        return $word;
    }

    protected function encodeLength($length)
    {
        if ($length < 0x80) {
            @fwrite($this->socket, chr($length));
        } elseif ($length < 0x4000) {
            $length |= 0x8000;
            @fwrite($this->socket, chr(($length >> 8) & 0xFF) . chr($length & 0xFF));
        } elseif ($length < 0x200000) {
            $length |= 0xC00000;
            @fwrite($this->socket, chr(($length >> 16) & 0xFF) . chr(($length >> 8) & 0xFF) . chr($length & 0xFF));
        }
    }

    protected function decodeLength(): int
    {
        $firstChar = @fread($this->socket, 1);
        if ($firstChar === false || $firstChar === '') return 0;

        $length = ord($firstChar);
        if (($length & 0x80) === 0x00) {
            return $length;
        } elseif (($length & 0xC0) === 0x80) {
            $length &= ~0x80;
            $length <<= 8;
            $char2 = @fread($this->socket, 1);
            if ($char2 !== false && $char2 !== '') {
                $length += ord($char2);
            }
            return $length;
        } elseif (($length & 0xE0) === 0xC0) {
            $length &= ~0xC0;
            $length <<= 8;
            $char2 = @fread($this->socket, 1);
            if ($char2 !== false && $char2 !== '') $length += ord($char2);
            $length <<= 8;
            $char3 = @fread($this->socket, 1);
            if ($char3 !== false && $char3 !== '') $length += ord($char3);
            return $length;
        } elseif (($length & 0xF0) === 0xE0) {
            $length &= ~0xE0;
            $length <<= 8;
            $char2 = @fread($this->socket, 1);
            if ($char2 !== false && $char2 !== '') $length += ord($char2);
            $length <<= 8;
            $char3 = @fread($this->socket, 1);
            if ($char3 !== false && $char3 !== '') $length += ord($char3);
            $length <<= 8;
            $char4 = @fread($this->socket, 1);
            if ($char4 !== false && $char4 !== '') $length += ord($char4);
            return $length;
        }
        return 0;
    }
}
