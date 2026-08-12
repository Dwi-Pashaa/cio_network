@extends('layouts.app-pages')

@section('title', 'Daftar Layanan Internet — CIO Network')

@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
    :root {
        --primary: #2563eb;
        --primary-dark: #1d4ed8;
        --primary-light: rgba(37,99,235,0.08);
        --success: #10b981;
        --success-dark: #059669;
        --danger: #ef4444;
        --dark: #0f172a;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --radius: 12px;
        --radius-lg: 20px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
        --shadow-md: 0 4px 16px rgba(0,0,0,0.07);
        --shadow-lg: 0 12px 40px rgba(0,0,0,0.1);
        --transition: 0.25s cubic-bezier(0.4,0,0.2,1);
    }

    *, *::before, *::after {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: linear-gradient(135deg, var(--gray-50) 0%, #eef2ff 50%, var(--gray-50) 100%);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem 1.5rem 4rem;
        margin: 0;
    }

    .ambient-orb {
        position: fixed;
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .ambient-orb--1 {
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(37,99,235,0.08) 0%, transparent 70%);
        top: -200px; left: -150px;
        animation: float 10s ease-in-out infinite;
    }
    .ambient-orb--2 {
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(16,185,129,0.07) 0%, transparent 70%);
        bottom: -250px; right: -200px;
        animation: float 12s ease-in-out infinite reverse;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-25px) scale(1.05); }
    }

    .wizard-card {
        position: relative;
        z-index: 1;
        background: #fff;
        border: 1px solid rgba(15,23,42,0.08);
        box-shadow: var(--shadow-lg);
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 700px;
        overflow: hidden;
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%);
        padding: 2.25rem 2rem 1.75rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .card-header-gradient::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 30% 50%, rgba(255,255,255,0.08) 0%, transparent 60%);
    }
    .brand-logo-container {
        display: inline-flex;
        gap: 10px;
        padding: 8px 18px;
        background: rgba(255,255,255,0.95);
        border-radius: 12px;
        align-items: center;
        margin-bottom: 0.85rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        position: relative;
    }
    .brand-logo-container img { height: 28px; width: auto; }
    .card-header-gradient h1 {
        color: #fff;
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin: 0 0 0.35rem;
        position: relative;
    }
    .card-header-gradient p {
        color: rgba(255,255,255,0.8);
        font-size: 0.85rem;
        margin: 0;
        font-weight: 500;
        position: relative;
    }

    .card-body-content { padding: 2rem 2.25rem 2.5rem; }

    /* Step Indicator */
    .steps {
        margin-bottom: 2rem;
    }
    .step-track {
        display: flex;
        align-items: center;
        width: 100%;
    }
    .step-point {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        gap: 8px;
    }
    .step-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all var(--transition);
        flex-shrink: 0;
        background: var(--gray-100);
        color: var(--gray-400);
        border: 2px solid var(--gray-200);
        font-weight: 700;
        font-size: 0.9rem;
    }
    .step-circle svg {
        width: 20px;
        height: 20px;
        display: block;
    }
    .step-circle .step-check { display: none; }
    .step-circle.active {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
        box-shadow: 0 0 0 4px rgba(37,99,235,0.25);
    }
    .step-circle.active svg { color: #fff; }
    .step-circle.done {
        background: var(--success);
        border-color: var(--success);
        color: #fff;
        box-shadow: 0 0 0 4px rgba(16,185,129,0.2);
    }
    .step-circle.done .step-icon { display: none; }
    .step-circle.done .step-check { display: block; }
    .step-circle.done svg { color: #fff; }
    .step-line {
        width: 60px;
        height: 3px;
        background: var(--gray-200);
        transition: background 0.4s;
        flex-shrink: 0;
        border-radius: 2px;
    }
    .step-line.done { background: var(--success); }
    .step-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--gray-400);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: color var(--transition);
        text-align: center;
        white-space: nowrap;
    }
    .step-label.active { color: var(--primary); }
    .step-label.done { color: var(--success); }

    /* Steps */
    .wizard-step {
        display: none;
        animation: slideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .wizard-step.active { display: block; }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Alerts */
    .alert-custom {
        padding: 0.85rem 1.15rem;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1.35rem;
        display: none;
        align-items: center;
        gap: 0.6rem;
        line-height: 1.4;
    }
    .alert-custom.error {
        display: flex;
        background: #fef2f2;
        border: 1.5px solid #fecaca;
        color: #b91c1c;
    }
    .alert-custom.success {
        display: flex;
        background: #f0fdf4;
        border: 1.5px solid #bbf7d0;
        color: #065f46;
    }

    /* Agreement Box */
    .agreement-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }
    .agreement-header svg {
        width: 22px;
        height: 22px;
        color: var(--primary);
        flex-shrink: 0;
    }
    .agreement-header h3 {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }
    .agreement-desc {
        font-size: 0.83rem;
        color: var(--gray-500);
        margin: 0 0 1rem;
        line-height: 1.6;
    }
    .agreement-box {
        height: 280px;
        overflow-y: auto;
        border: 1.5px solid var(--gray-200);
        border-radius: var(--radius);
        padding: 1.25rem 1.5rem;
        background: #fafafa;
        margin-bottom: 1rem;
        font-size: 0.85rem;
        line-height: 1.8;
        color: var(--gray-600);
    }
    .agreement-box h1,
    .agreement-box h2,
    .agreement-box h3 {
        font-size: 0.95rem;
        margin: 1.25rem 0 0.5rem;
        color: var(--dark);
        font-weight: 700;
    }
    .agreement-box h1:first-child,
    .agreement-box h2:first-child,
    .agreement-box h3:first-child {
        margin-top: 0;
    }
    .agreement-box p { margin: 0 0 0.5rem; }

    /* Checkbox */
    .checkbox-wrapper {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin: 1.25rem 0;
        background: rgba(37,99,235,0.03);
        padding: 0.85rem 1rem;
        border-radius: var(--radius);
        border: 1px dashed rgba(37,99,235,0.18);
    }
    .checkbox-wrapper input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-top: 2px;
        accent-color: var(--primary);
        cursor: pointer;
        flex-shrink: 0;
    }
    .checkbox-wrapper label {
        font-size: 0.86rem;
        font-weight: 600;
        color: var(--dark);
        cursor: pointer;
        line-height: 1.5;
    }

    /* Form Sections & Elements */
    .form-section-title {
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--primary);
        margin: 0.5rem 0 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .form-grid {
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .form-group-full { min-width: 0; }
    .form-group { min-width: 0; display: flex; flex-direction: column; }

    .form-label-custom {
        font-size: 0.73rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--gray-600);
        margin-bottom: 0.4rem;
        display: block;
    }
    .text-danger { color: var(--danger); }

    .input-wrapper {
        position: relative;
    }
    .input-icon {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: var(--gray-400);
        pointer-events: none;
        transition: color var(--transition);
        z-index: 1;
    }
    .input-wrapper:focus-within .input-icon { color: var(--primary); }

    .form-control-custom {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        font-size: 0.9rem;
        font-weight: 500;
        background: #fff;
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        color: var(--dark);
        transition: all var(--transition);
        outline: none;
        min-height: 44px;
        font-family: inherit;
    }
    .form-control-custom:hover {
        border-color: var(--gray-300);
    }
    .form-control-custom:focus {
        background: #fff;
        border-color: var(--primary);
        box-shadow: 0 0 0 3.5px rgba(37,99,235,0.15);
    }
    .form-control-custom.is-invalid {
        border-color: var(--danger) !important;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.15) !important;
    }
    .form-control-custom:disabled {
        background: var(--gray-100);
        cursor: not-allowed;
        color: var(--gray-400);
    }
    .form-control-custom::placeholder {
        color: var(--gray-400);
        font-weight: 400;
    }

    select.form-control-custom {
        appearance: none;
        padding-right: 2.5rem;
        cursor: pointer;
    }
    .chevron-down {
        position: absolute;
        right: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: var(--gray-400);
        pointer-events: none;
        transition: color var(--transition);
    }
    .input-wrapper:focus-within .chevron-down { color: var(--primary); }

    /* Dynamic WiFi Box */
    .wifi-config-box {
        background: linear-gradient(135deg, rgba(37,99,235,0.03) 0%, rgba(37,99,235,0.07) 100%);
        border: 1.5px solid rgba(37,99,235,0.22);
        border-radius: var(--radius);
        padding: 1.15rem 1.25rem;
        animation: fadeInSlide 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes fadeInSlide {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .wifi-box-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.85rem;
    }
    .wifi-box-title {
        font-size: 0.82rem;
        font-weight: 800;
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    .badge-required {
        background: rgba(37,99,235,0.12);
        color: var(--primary-dark);
        font-size: 0.65rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .btn-toggle-pwd {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--gray-400);
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color var(--transition);
    }
    .btn-toggle-pwd:hover { color: var(--primary); }
    .form-hint {
        font-size: 0.72rem;
        color: var(--gray-500);
        margin-top: 0.3rem;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        font-family: inherit;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all var(--transition);
        text-decoration: none;
        line-height: 1;
    }
    .btn:disabled {
        opacity: 0.45;
        cursor: not-allowed;
        transform: none !important;
    }

    .btn-primary {
        width: 100%;
        padding: 0.85rem 1.5rem;
        font-size: 0.9rem;
        background: var(--primary);
        color: #fff;
        border-radius: 10px;
        box-shadow: 0 8px 20px -5px rgba(37,99,235,0.35);
    }
    .btn-primary:hover:not(:disabled) {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 12px 25px -5px rgba(37,99,235,0.4);
    }

    .btn-secondary {
        padding: 0.7rem 1.5rem;
        font-size: 0.86rem;
        font-weight: 600;
        color: var(--gray-600);
        background: #fff;
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
    }
    .btn-secondary:hover:not(:disabled) {
        background: var(--gray-100);
        color: var(--dark);
        border-color: var(--gray-300);
    }

    .btn-success {
        width: 100%;
        padding: 0.85rem 1.75rem;
        font-size: 0.9rem;
        background: var(--success);
        color: #fff;
        border-radius: 10px;
        box-shadow: 0 8px 20px -5px rgba(16,185,129,0.35);
    }
    .btn-success:hover:not(:disabled) {
        background: var(--success-dark);
        transform: translateY(-1px);
        box-shadow: 0 12px 25px -5px rgba(16,185,129,0.4);
    }

    .nav-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-top: 2rem;
    }

    /* Summary Card */
    .summary-card {
        background: linear-gradient(135deg, rgba(37,99,235,0.04) 0%, rgba(37,99,235,0.02) 100%);
        border: 1.5px solid rgba(37,99,235,0.15);
        border-radius: var(--radius);
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.6rem 0;
        border-bottom: 1px dashed rgba(37,99,235,0.12);
        gap: 1rem;
    }
    .summary-item:last-child { border-bottom: none; }
    .summary-lbl {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--gray-500);
        flex-shrink: 0;
    }
    .summary-val {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--dark);
        text-align: right;
        word-break: break-word;
    }
    .wifi-highlight-val {
        background: rgba(37,99,235,0.08);
        color: var(--primary-dark);
        padding: 2px 8px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 0.88rem;
    }

    /* Signature Pad */
    .signature-container {
        position: relative;
        background: #fff;
        border: 2px dashed var(--gray-300);
        border-radius: var(--radius);
        /* overflow:hidden REMOVED — it blocks pointer capture in some browsers */
        transition: border-color var(--transition);
        touch-action: none; /* prevent scroll hijacking on touchpad */
        user-select: none;
    }
    .signature-container:focus-within {
        border-color: var(--primary);
    }
    #signature-pad {
        width: 100%;
        height: 200px;
        background: #fff;
        cursor: crosshair;
        touch-action: none; /* required for PointerEvents-based signature_pad@4 */
        display: block;
        border-radius: var(--radius);
    }
    .btn-reset-signature {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        z-index: 2;
        color: var(--gray-500);
        transition: all var(--transition);
        font-family: inherit;
    }
    .btn-reset-signature:hover {
        background: #fef2f2;
        color: var(--danger);
        border-color: #fca5a5;
    }
    .signature-hint {
        font-size: 0.73rem;
        color: var(--gray-400);
        margin-top: 0.4rem;
        text-align: center;
    }

    /* ── Document Preview Step 4 ───────────────────────────── */
    .preview-container {
        width: 100%;
        max-height: 400px;
        overflow-y: auto;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius);
        background: #fff;
        padding: 1.5rem;
        text-align: left;
        margin: 1.5rem 0;
        box-shadow: inset 0 2px 8px rgba(0,0,0,0.03);
    }
    .preview-doc {
        font-size: 0.85rem;
        color: var(--dark);
        line-height: 1.6;
    }
    .preview-header {
        border-bottom: 2px solid var(--gray-200);
        padding-bottom: 1rem;
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .preview-header-title {
        font-weight: 800;
        font-size: 1rem;
        color: var(--primary-dark);
    }
    .preview-header-code {
        background: var(--primary-light);
        color: var(--primary-dark);
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
    }
    .preview-section {
        margin-bottom: 1.25rem;
    }
    .preview-section h4 {
        margin: 0 0 0.5rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        color: var(--gray-500);
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--gray-100);
        padding-bottom: 0.25rem;
    }
    .preview-table {
        width: 100%;
        border-collapse: collapse;
    }
    .preview-table td {
        padding: 0.4rem 0;
        border-bottom: 1px solid var(--gray-50);
        vertical-align: top;
    }
    .preview-table td:first-child {
        width: 35%;
        color: var(--gray-500);
        font-weight: 500;
    }
    .preview-table td:last-child {
        color: var(--dark);
        font-weight: 600;
    }
    .preview-body-content {
        background: var(--gray-50);
        padding: 1rem;
        border-radius: 8px;
        font-size: 0.8rem;
        color: var(--gray-600);
        max-height: 150px;
        overflow-y: auto;
        border: 1px solid var(--gray-200);
    }
    .preview-signatures {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-top: 1.5rem;
        border-top: 1px solid var(--gray-200);
        padding-top: 1rem;
    }
    .preview-sig-box {
        text-align: center;
    }
    .preview-sig-img {
        max-height: 60px;
        max-width: 120px;
        display: block;
        margin: 0.5rem auto;
        mix-blend-mode: multiply;
    }
    .preview-sig-box p {
        margin: 0;
        font-size: 0.75rem;
        color: var(--gray-500);
    }
    .preview-actions {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.75rem;
        width: 100%;
        margin-top: 1rem;
    }
    @media (min-width: 480px) {
        .preview-actions {
            grid-template-columns: 1fr 1fr;
        }
    }

    /* Success Step */
    .success-wrap {
        text-align: center;
        padding: 1rem 0;
    }
    .success-icon {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--success) 0%, #34d399 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        box-shadow: 0 10px 30px -5px rgba(16,185,129,0.35);
        animation: bounceIn 0.6s cubic-bezier(0.68,-0.55,0.265,1.55);
    }
    @keyframes bounceIn {
        0% { transform: scale(0); }
        50% { transform: scale(1.12); }
        100% { transform: scale(1); }
    }
    .success-title {
        color: var(--dark);
        font-size: 1.35rem;
        font-weight: 800;
        margin: 0 0 0.35rem;
    }
    .success-subtitle {
        color: var(--gray-500);
        font-size: 0.88rem;
        margin-bottom: 1.15rem;
    }
    .code-badge {
        background: #f0fdf4;
        border: 1.5px solid #86efac;
        border-radius: 12px;
        padding: 0.7rem 1.75rem;
        font-family: 'Courier New', monospace;
        font-size: 1.25rem;
        font-weight: 800;
        color: #065f46;
        display: inline-block;
        letter-spacing: 0.06em;
        margin: 0 0 1.15rem;
        box-shadow: 0 4px 12px rgba(16,185,129,0.1);
    }
    .success-note {
        color: var(--gray-500);
        font-size: 0.82rem;
        margin: 0.5rem 0 1.5rem;
        line-height: 1.6;
    }
    .btn-download {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: auto;
        padding: 0.85rem 2rem;
        font-size: 0.9rem;
        font-weight: 700;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 10px;
        text-decoration: none;
        cursor: pointer;
        transition: all var(--transition);
        box-shadow: 0 8px 20px -5px rgba(37,99,235,0.35);
    }
    .btn-download:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }
    .btn-register-again {
        width: 100%;
        margin-top: 0.85rem;
    }

    /* Responsive */
    @media (max-width: 767px) {
        .form-row { grid-template-columns: 1fr; }
        .step-line { width: 28px; }
        .step-circle { width: 38px; height: 38px; font-size: 0.82rem; }
        .step-circle svg { width: 18px; height: 18px; }
        .step-label { font-size: 0.62rem; white-space: normal; }
    }
    @media (max-width: 480px) {
        body { padding: 1.25rem 1rem 3rem; }
        .card-body-content { padding: 1.5rem; }
        .card-header-gradient { padding: 1.75rem 1.25rem 1.5rem; }
        .card-header-gradient h1 { font-size: 1.2rem; }
        .step-line { width: 14px; }
        .step-circle { width: 34px; height: 34px; font-size: 0.78rem; }
        .step-circle svg { width: 16px; height: 16px; }
        .step-label { font-size: 0.58rem; white-space: normal; }
        .nav-buttons { flex-direction: column-reverse; gap: 0.75rem; }
        .nav-buttons .btn { width: 100%; }
        .summary-card { padding: 1rem 1.15rem; }
    }

    /* ── Pages Radio Card Grid ─────────────────────────────── */
    .pages-radio-wrapper {
        width: 100%;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius);
        min-height: 100px;
        background: var(--gray-50);
        transition: border-color var(--transition);
        overflow: hidden;
    }
    .pages-radio-wrapper.is-invalid-group {
        border-color: var(--danger);
        background: rgba(239,68,68,0.03);
        animation: shake 0.4s cubic-bezier(.36,.07,.19,.97);
    }
    @keyframes shake {
        0%,100% { transform: translateX(0); }
        20%,60% { transform: translateX(-5px); }
        40%,80% { transform: translateX(5px); }
    }
    .pages-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.65rem;
        padding: 2rem 1rem;
        color: var(--gray-400);
        font-size: 0.875rem;
        text-align: center;
        line-height: 1.5;
    }
    .pages-placeholder svg { opacity: 0.5; flex-shrink: 0; }
    .pages-placeholder strong { color: var(--primary); }
    .pages-loading {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        font-size: 0.875rem;
        color: var(--gray-500);
    }
    .pages-spinner {
        width: 18px;
        height: 18px;
        border: 2.5px solid var(--gray-200);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 0.75s linear infinite;
        display: inline-block;
        flex-shrink: 0;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Grid layout */
    .pages-radio-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 0.75rem;
        padding: 1rem;
    }

    /* Each radio card */
    .page-radio-card {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.55rem;
        padding: 1rem 0.75rem 0.9rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius);
        background: #fff;
        cursor: pointer;
        transition: border-color var(--transition), box-shadow var(--transition), transform var(--transition), background var(--transition);
        text-align: center;
        user-select: none;
    }
    .page-radio-card:hover {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37,99,235,0.08);
        transform: translateY(-2px);
    }
    .page-radio-card.selected {
        border-color: var(--primary);
        background: var(--primary-light);
        box-shadow: 0 0 0 4px rgba(37,99,235,0.12);
    }

    /* Hide the actual radio input visually */
    .page-radio-card input[type="radio"] {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }

    /* Icon area */
    .page-radio-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background var(--transition);
    }
    .page-radio-icon svg {
        width: 22px;
        height: 22px;
        stroke: var(--primary);
        transition: stroke var(--transition);
    }
    .page-radio-card.selected .page-radio-icon {
        background: var(--primary);
    }
    .page-radio-card.selected .page-radio-icon svg {
        stroke: #fff;
    }

    /* Name text */
    .page-radio-name {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--gray-600);
        line-height: 1.3;
        transition: color var(--transition);
    }
    .page-radio-card.selected .page-radio-name {
        color: var(--primary-dark);
    }

    /* Checkmark badge */
    .page-radio-check {
        position: absolute;
        top: 0.45rem;
        right: 0.45rem;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transform: scale(0.5);
        transition: opacity 0.2s ease, transform 0.2s ease;
    }
    .page-radio-check svg {
        width: 12px;
        height: 12px;
        stroke: #fff;
    }
    .page-radio-card.selected .page-radio-check {
        opacity: 1;
        transform: scale(1);
    }

    /* Responsive */
    @media (max-width: 600px) {
        .pages-radio-grid {
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 0.6rem;
            padding: 0.75rem;
        }
        .page-radio-card { padding: 0.85rem 0.6rem 0.75rem; }
        .page-radio-icon { width: 38px; height: 38px; }
        .page-radio-icon svg { width: 18px; height: 18px; }
        .page-radio-name { font-size: 0.78rem; }
    }

    /* ── Select2 Custom Theme ───────────────────────────────── */
    .select2-container {
        width: 100% !important;
    }
    .select2-container--default .select2-selection--single {
        height: 52px;
        border: 1.5px solid var(--gray-200);
        border-radius: var(--radius);
        background: var(--gray-50);
        display: flex;
        align-items: center;
        transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
        padding: 0 1rem;
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
        outline: none;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--dark);
        font-size: 0.95rem;
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        line-height: 1;
        padding: 0;
        margin: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: var(--gray-400);
        font-weight: 400;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 50px;
        right: 12px;
        width: 20px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: var(--gray-400) transparent transparent transparent;
        border-width: 5px 4px 0 4px;
        top: 55%;
    }
    .select2-container--default.select2-container--open .select2-selection__arrow b {
        border-color: transparent transparent var(--primary) transparent;
        border-width: 0 4px 5px 4px;
    }
    .select2-dropdown {
        border: 1.5px solid var(--primary);
        border-radius: var(--radius) !important;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        font-family: 'Inter', sans-serif;
        margin-top: 4px;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1.5px solid var(--gray-200);
        border-radius: 8px;
        padding: 0.45rem 0.75rem;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        color: var(--dark);
        outline: none;
        width: 100%;
        transition: border-color var(--transition);
    }
    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(37,99,235,0.1);
    }
    .select2-container--default .select2-results__option {
        font-size: 0.9rem;
        padding: 0.6rem 0.85rem;
        color: var(--gray-600);
        transition: background 0.15s;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: var(--primary-light);
        color: var(--primary-dark);
        font-weight: 500;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background: var(--primary);
        color: #fff;
        font-weight: 600;
    }
    .select2-search--dropdown { padding: 8px; }

    /* ── Paket Price Box (redesigned) ─────────────────────── */
    .paket-price-box {
        margin-top: 1rem;
        background: #fff;
        border: 1.5px solid rgba(37,99,235,0.25);
        border-radius: var(--radius);
        padding: 1.25rem 1.25rem 1.5rem;
        box-shadow: var(--shadow-sm);
        animation: fadeInSlide 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .paket-price-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid rgba(37,99,235,0.15);
    }
    .paket-price-title {
        font-size: 0.85rem;
        font-weight: 800;
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    .paket-price-title svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    /* ── Paket Radio Cards ────────────────────────────────── */
    .paket-radio-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }
    .paket-radio-card {
        position: relative;
        display: flex;
        flex-direction: column;
        padding: 1rem 1rem 0.9rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius);
        background: #fff;
        cursor: pointer;
        transition: all var(--transition);
        user-select: none;
    }
    .paket-radio-card:hover {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37,99,235,0.08);
        transform: translateY(-2px);
    }
    .paket-radio-card.selected {
        border-color: var(--primary);
        background: rgba(37,99,235,0.04);
        box-shadow: 0 0 0 4px rgba(37,99,235,0.12), 0 4px 12px rgba(37,99,235,0.08);
    }
    .paket-radio-card input[type="radio"] {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }
    .paket-radio-name {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--dark);
        line-height: 1.3;
        margin-bottom: 0.35rem;
        transition: color var(--transition);
    }
    .paket-radio-card.selected .paket-radio-name {
        color: var(--primary-dark);
    }
    .paket-radio-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--primary-dark);
        background: rgba(37,99,235,0.1);
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
        margin-bottom: 0.4rem;
        align-self: flex-start;
    }
    .paket-radio-badge svg {
        width: 13px;
        height: 13px;
    }
    .paket-radio-check {
        position: absolute;
        top: 0.45rem;
        right: 0.45rem;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transform: scale(0.5);
        transition: opacity 0.2s ease, transform 0.2s ease;
    }
    .paket-radio-check svg {
        width: 12px;
        height: 12px;
        stroke: #fff;
    }
    .paket-radio-card.selected .paket-radio-check {
        opacity: 1;
        transform: scale(1);
    }
    .paket-placeholder {
        grid-column: 1 / -1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        padding: 1.5rem;
        color: var(--gray-400);
        font-size: 0.85rem;
    }

    /* ── Payment Section ──────────────────────────────────── */
    .payment-section {
        animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        padding-top: 0.5rem;
    }
    .payment-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        margin-bottom: 1rem;
    }
    .payment-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.6rem 1.1rem;
        font-size: 0.82rem;
        font-weight: 600;
        font-family: inherit;
        color: var(--gray-600);
        background: #fff;
        border: 1.5px solid var(--gray-200);
        border-radius: 24px;
        cursor: pointer;
        transition: all var(--transition);
        user-select: none;
        white-space: nowrap;
    }
    .payment-pill:hover {
        border-color: var(--primary);
        color: var(--primary-dark);
        background: rgba(37,99,235,0.04);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
    }
    .payment-pill.active {
        border-color: var(--primary);
        color: #fff;
        background: var(--primary);
        box-shadow: 0 4px 12px rgba(37,99,235,0.25);
    }
    .payment-pill svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }
    .payment-pill.active svg {
        color: #fff;
    }

    /* ── Payment Info Card ────────────────────────────────── */
    .payment-info-card {
        margin-top: 1rem;
        background: linear-gradient(135deg, rgba(37,99,235,0.04) 0%, rgba(37,99,235,0.02) 100%);
        border: 1.5px solid rgba(37,99,235,0.2);
        border-radius: var(--radius);
        padding: 1rem 1.15rem;
        animation: fadeInSlide 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .payment-info-header {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        margin-bottom: 0.6rem;
    }
    .payment-info-header svg {
        width: 18px;
        height: 18px;
        color: var(--primary-dark);
        flex-shrink: 0;
    }
    .payment-info-header span {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--primary-dark);
    }
    .payment-info-text {
        font-size: 0.86rem;
        color: var(--gray-600);
        line-height: 1.6;
        white-space: pre-wrap;
        margin-bottom: 0.75rem;
    }
    .payment-account-display {
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: 10px;
        padding: 0.85rem 1rem;
        margin-top: 0.5rem;
    }
    .account-number-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-top: 0.25rem;
    }
    .account-number {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--dark);
        font-family: 'Courier New', monospace;
        letter-spacing: 0.03em;
        word-break: break-all;
        flex: 1;
    }
    .copy-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border: 1.5px solid var(--gray-200);
        border-radius: 8px;
        background: #fff;
        cursor: pointer;
        transition: all var(--transition);
        flex-shrink: 0;
        color: var(--gray-400);
    }
    .copy-btn:hover {
        border-color: var(--primary);
        color: var(--primary-dark);
        background: rgba(37,99,235,0.05);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
    }
    .copy-btn svg {
        width: 17px;
        height: 17px;
    }
    .copy-btn.copied {
        border-color: var(--primary);
        background: rgba(37,99,235,0.1);
        color: var(--primary-dark);
    }

    /* ── Dropzone Upload ──────────────────────────────────── */
    .dropzone-upload {
        position: relative;
        border: 2px dashed var(--gray-300);
        border-radius: var(--radius);
        background: rgba(16,185,129,0.02);
        transition: border-color var(--transition), background var(--transition);
        cursor: pointer;
        min-height: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .dropzone-upload:hover,
    .dropzone-upload.dragover {
        border-color: var(--success);
        background: rgba(16,185,129,0.05);
    }
    .dropzone-upload.has-file {
        border-style: solid;
        border-color: var(--success);
        background: rgba(16,185,129,0.03);
    }
    .dropzone-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.35rem;
        padding: 1.25rem 1rem;
        text-align: center;
        pointer-events: none;
    }
    .dropzone-content svg {
        width: 32px;
        height: 32px;
        color: var(--gray-400);
        transition: color var(--transition);
    }
    .dropzone-upload:hover .dropzone-content svg,
    .dropzone-upload.dragover .dropzone-content svg {
        color: var(--success);
    }
    .dropzone-text {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--gray-500);
        margin: 0;
        line-height: 1.4;
    }
    .dropzone-upload:hover .dropzone-text,
    .dropzone-upload.dragover .dropzone-text {
        color: var(--success-dark);
    }
    .dropzone-hint {
        font-size: 0.72rem;
        color: var(--gray-400);
    }
    .dropzone-preview {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        width: 100%;
        padding: 0.65rem 1rem;
        pointer-events: none;
    }
    .dropzone-preview-info {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        min-width: 0;
    }
    .dropzone-file-icon {
        width: 24px;
        height: 24px;
        color: var(--success);
        flex-shrink: 0;
    }
    .dropzone-filename {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--dark);
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 200px;
    }
    .dropzone-filesize {
        font-size: 0.7rem;
        color: var(--gray-400);
        display: block;
    }
    .dropzone-remove-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: 1.5px solid transparent;
        border-radius: 8px;
        background: rgba(239,68,68,0.08);
        cursor: pointer;
        color: var(--danger);
        transition: all var(--transition);
        pointer-events: auto;
        flex-shrink: 0;
    }
    .dropzone-remove-btn:hover {
        background: rgba(239,68,68,0.15);
        border-color: rgba(239,68,68,0.2);
    }
    .dropzone-remove-btn svg {
        width: 16px;
        height: 16px;
    }

    @media (max-width: 600px) {
        .paket-radio-grid {
            grid-template-columns: 1fr 1fr;
            gap: 0.6rem;
        }
        .paket-radio-card { padding: 0.85rem 0.75rem 0.8rem; }
        .paket-radio-name { font-size: 0.82rem; }
        .payment-pills { gap: 0.4rem; }
        .payment-pill { padding: 0.5rem 0.85rem; font-size: 0.78rem; }
        .account-number { font-size: 0.92rem; }
        .dropzone-filename { max-width: 140px; }
    }
    @media (max-width: 400px) {
        .paket-radio-grid { grid-template-columns: 1fr; }
    }
    </style>
@endpush

@section('content')
    <div class="ambient-orb ambient-orb--1"></div>
    <div class="ambient-orb ambient-orb--2"></div>

    <div class="wizard-card">
        <div class="card-header-gradient">
            <div class="brand-logo-container">
                <img src="{{ asset('img/logo_2.jpeg') }}" alt="Logo CIO">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo CIO">
            </div>
            <h1>Daftar Layanan Internet</h1>
            <p>Lengkapi formulir di bawah ini untuk pendaftaran pemasangan baru</p>
        </div>

        <div class="card-body-content">
            <div class="steps">
                <div class="step-track">
                    <div class="step-point">
                        <div class="step-circle active" id="s1">
                            <span class="step-icon">1</span>
                            <svg class="step-check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span class="step-label active" id="sl1">Persetujuan</span>
                    </div>
                    <div class="step-line" id="l1"></div>
                    <div class="step-point">
                        <div class="step-circle" id="s2">
                            <span class="step-icon">2</span>
                            <svg class="step-check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span class="step-label" id="sl2">Data Form</span>
                    </div>
                    <div class="step-line" id="l2"></div>
                    <div class="step-point">
                        <div class="step-circle" id="s3">
                            <span class="step-icon">3</span>
                            <svg class="step-check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span class="step-label" id="sl3">Konfirmasi</span>
                    </div>
                    <div class="step-line" id="l3"></div>
                    <div class="step-point">
                        <div class="step-circle" id="s4">
                            <span class="step-icon">4</span>
                            <svg class="step-check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span class="step-label" id="sl4">Selesai</span>
                    </div>
                </div>
            </div>

            <div class="alert-custom" id="alertError" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span id="alertErrorText"></span>
            </div>
            <div class="alert-custom" id="alertSuccess" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span id="alertSuccessText"></span>
            </div>

            {{-- STEP 1: Persetujuan --}}
            <div class="wizard-step active" id="step1">
                @if($persetujuan)
                    <div class="agreement-header">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        <h3>Dokumen Syarat & Ketentuan Layanan</h3>
                    </div>
                    <p class="agreement-desc">Silakan baca dokumen ketentuan berikut dengan saksama sebelum melanjutkan ke pengisian formulir data diri.</p>
                    <div class="agreement-box">{!! $persetujuan->konten !!}</div>
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="agreeCheck">
                        <label for="agreeCheck">Saya telah membaca dan menyetujui seluruh ketentuan layanan di atas</label>
                    </div>
                    <input type="hidden" id="persetujuanId" value="{{ $persetujuan->id }}">
                    <input type="hidden" id="submission_token" value="{{ $submissionToken }}">
                    <button class="btn btn-primary" id="btnStep1Next" disabled>Selanjutnya</button>
                @else
                    <div class="alert-custom error" style="display:flex;">Belum ada dokumen persetujuan aktif. Silakan hubungi administrator.</div>
                @endif
            </div>

            {{-- STEP 2: Form Pendaftaran --}}
            <div class="wizard-step" id="step2">
                <div class="form-grid">

                    <div class="form-section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Informasi Pelanggan
                    </div>

                    <div class="form-group-full">
                        <label class="form-label-custom" for="f_nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <input type="text" id="f_nama" class="form-control-custom" placeholder="Nama sesuai KTP" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label-custom" for="f_no_telepon">No. WhatsApp / Telepon <span class="text-danger">*</span></label>
                            <div class="input-wrapper">
                                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                <input type="tel" id="f_no_telepon" class="form-control-custom" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom" for="f_email">Email</label>
                            <div class="input-wrapper">
                                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 4L12 13 2 4"/></svg>
                                <input type="email" id="f_email" class="form-control-custom" placeholder="alamat@email.com" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-title" style="margin-top:0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                        Pilihan Layanan Internet
                    </div>

                    <div class="form-group-full">
                        <label class="form-label-custom" for="f_tipe_layanan">Tipe Layanan <span class="text-danger">*</span></label>
                        <select id="f_tipe_layanan" class="form-control-custom" required>
                            <option value="">-- Pilih Tipe Layanan --</option>
                            @foreach($tipeLayanan as $tl)
                                <option value="{{ $tl->id }}">{{ $tl->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- DYNAMIC WIFI CONFIGURATION BOX (Show when PPPoE) --}}
                    <div class="wifi-config-box" id="wifi_box" style="display: none;">
                        <div class="wifi-box-header">
                            <div class="wifi-box-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
                                Pengaturan WiFi (Layanan PPPoE)
                            </div>
                            <span class="badge-required">Wajib Diisi</span>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label-custom" for="f_name_wifi">Nama WiFi (SSID) <span class="text-danger">*</span></label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
                                    <input type="text" id="f_name_wifi" class="form-control-custom" placeholder="Contoh: CIO_RumahKu">
                                </div>
                                <span class="form-hint">Nama sinyal wifi yang akan dipasang</span>
                            </div>

                            <div class="form-group">
                                <label class="form-label-custom" for="f_password_wifi">Password WiFi <span class="text-danger">*</span></label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    <input type="password" id="f_password_wifi" class="form-control-custom" placeholder="Min. 4 - 8 karakter" style="padding-right: 2.75rem;">
                                    <button type="button" class="btn-toggle-pwd" id="btnToggleWifiPass" title="Tampilkan/Sembunyikan Password">
                                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                </div>
                                <span class="form-hint">Password untuk koneksi ke sinyal wifi</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-title" style="margin-top:0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Lokasi Pemasangan
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label-custom" for="f_kampung">Kampung <span class="text-danger">*</span></label>
                            <select id="f_kampung" class="form-control-custom" required>
                                <option value="">-- Pilih Kampung --</option>
                                @foreach($kampung as $k)
                                    <option value="{{ $k->id }}">{{ $k->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom" for="f_desa">Desa <span class="text-danger">*</span></label>
                            <select id="f_desa" class="form-control-custom" required>
                                <option value="">-- Pilih Desa --</option>
                                @foreach($desa as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group-full">
                        <label class="form-label-custom">Data Halaman / Wilayah <span class="text-danger">*</span></label>
                        <div id="pages-radio-wrapper" class="pages-radio-wrapper">
                            <div id="pages-radio-placeholder" class="pages-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span>Pilih <strong>Desa</strong> terlebih dahulu untuk melihat pilihan halaman wilayah</span>
                            </div>
                            <div id="pages-radio-grid" class="pages-radio-grid" style="display:none;"></div>
                        </div>
                        <input type="hidden" id="f_pages" name="pages_id" value="">
                    </div>

                    {{-- DYNAMIC PAKET & PRICE SELECTION (Show after pages_id selected) --}}
                    <div class="paket-price-box" id="paket_price_box" style="display: none;">
                        <div class="paket-price-header">
                            <div class="paket-price-title">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Pilihan Paket & Tipe Pembayaran
                            </div>
                            <span class="badge-required" style="background: rgba(37,99,235,0.12); color: var(--primary-dark);">Wajib Diisi</span>
                        </div>

                        {{-- Step 1: Pilih Paket (radio cards full width) --}}
                        <div class="paket-section" id="paket_section">
                            <label class="form-label-custom" style="margin-bottom:0.6rem;">Pilih Paket Layanan <span class="text-danger">*</span></label>
                            <div id="paket-radio-grid" class="paket-radio-grid">
                                <div class="paket-placeholder" id="paket-placeholder">
                                    <span>Memuat data paket...</span>
                                </div>
                            </div>
                            <input type="hidden" id="f_paket" value="">
                        </div>

                        {{-- Step 2: Tipe Pembayaran (appears after paket selected) --}}
                        <div id="payment_section" class="payment-section" style="display: none;">
                            <label class="form-label-custom" style="margin-bottom:0.6rem;">Tipe Pembayaran <span class="text-danger">*</span></label>
                            <div id="payment-pills-container" class="payment-pills">
                                @foreach($tipePembayaran as $tp)
                                    <button type="button" class="payment-pill" value="{{ $tp->id }}"
                                        data-description="{{ $tp->description }}"
                                        data-use-bukti-bayar="{{ $tp->use_bukti_bayar ? '1' : '0' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                        {{ $tp->name }}
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" id="f_price" value="">

                            {{-- Payment Info Card --}}
                            <div id="payment_info_card" class="payment-info-card" style="display: none;">
                                <div class="payment-info-header">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                    <span>Informasi Pembayaran</span>
                                </div>
                                <div id="payment_info_text" class="payment-info-text"></div>
                                <div id="payment_account_display" class="payment-account-display" style="display: none;">
                                    <label class="form-label-custom" style="margin-bottom:0.3rem;font-size:0.68rem;">Nomor Rekening / Tujuan Pembayaran</label>
                                    <div class="account-number-wrapper">
                                        <span class="account-number" id="account_number_text"></span>
                                        <button class="copy-btn" id="copyAccountBtn" type="button" title="Salin nomor tujuan">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Upload Bukti Pembayaran (dropzone) --}}
                            <div id="payment_upload_group" style="display: none; margin-top: 1rem;">
                                <label class="form-label-custom" style="margin-bottom:0.45rem;">Upload Bukti Pembayaran</label>
                                <div class="dropzone-upload" id="dropzone-upload">
                                    <div class="dropzone-content" id="dropzone-content">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                        <p class="dropzone-text">Klik atau seret file bukti pembayaran ke sini</p>
                                        <span class="dropzone-hint">Format: JPG, PNG, PDF (maks. 2MB)</span>
                                    </div>
                                    <div class="dropzone-preview" id="dropzone-preview" style="display: none;">
                                        <div class="dropzone-preview-info">
                                            <svg class="dropzone-file-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                            <div>
                                                <span class="dropzone-filename" id="file-name-display"></span>
                                                <span class="dropzone-filesize" id="file-size-display"></span>
                                            </div>
                                        </div>
                                        <button type="button" class="dropzone-remove-btn" id="btn-remove-file" title="Hapus file">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </div>
                                    <input type="file" id="f_bukti_bayar" accept="image/*,.pdf" style="display: none;">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="nav-buttons">
                    <button class="btn btn-secondary" type="button" onclick="goStep(1)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                        Kembali
                    </button>
                    <button class="btn btn-primary" id="btnStep2Next" type="button" style="width:auto;padding:0.75rem 2.25rem;">
                        Konfirmasi
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </div>

            {{-- STEP 3: Konfirmasi & Tanda Tangan --}}
            <div class="wizard-step" id="step3">
                <div class="summary-card" id="summaryData"></div>

                <div>
                    <label class="form-label-custom">Tanda Tangan Digital Pelanggan <span class="text-danger">*</span></label>
                    <div class="signature-container">
                        <canvas id="signature-pad"></canvas>
                        <button class="btn-reset-signature" id="btnResetSignature" type="button">Hapus & Ulang</button>
                    </div>
                    <p class="signature-hint">Gunakan jari tangan Anda (HP/Tablet), mouse, atau <strong>touchpad laptop</strong> untuk menandatangani kotak di atas.</p>
                </div>

                <div class="nav-buttons">
                    <button class="btn btn-secondary" type="button" onclick="goStep(2)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                        Kembali
                    </button>
                    <button class="btn btn-success" id="btnSubmit" type="button" style="width:auto;padding:0.75rem 2.25rem;">
                        Selesai
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- STEP 4: Selesai --}}
            <div class="wizard-step" id="step4">
                <div class="success-wrap">
                    <div class="success-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <h3 class="success-title">Pendaftaran Berhasil Dikirim!</h3>
                    <p class="success-subtitle">Kode pendaftaran Anda adalah:</p>
                    <div class="code-badge" id="kodePendaftaran"></div>
                    <p class="success-note">Terima kasih telah mendaftar. Berikut adalah pratinjau surat perjanjian berlangganan WiFi Anda:</p>
                    
                    {{-- Scrollable Document Preview --}}
                    <div class="preview-container">
                        <div class="preview-doc" id="documentPreview">
                            <!-- Preview content will be injected dynamically via AJAX/JS on success -->
                        </div>
                    </div>

                    <div class="preview-actions">
                        <a id="btnDownloadPdf" class="btn btn-primary btn-download" style="margin:0; width:100%;" href="#" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Unduh PDF Perjanjian
                        </a>
                        <button class="btn btn-secondary" type="button" style="width:100%;" onclick="resetWizard()">Daftar Pelanggan Baru</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
    (function() {
        let currentStep = 1;
        let signaturePad = null;
        let formData = {};
        let availablePages = [];

        function goStep(step) {
            document.querySelectorAll('.wizard-step').forEach(el => el.classList.remove('active'));
            document.getElementById('step' + step).classList.add('active');
            currentStep = step;
            updateIndicator(step);
            hideAlerts();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function updateIndicator(step) {
            for (let i = 1; i <= 4; i++) {
                const circle = document.getElementById('s' + i);
                const label = document.getElementById('sl' + i);
                circle.classList.remove('active', 'done');
                label.classList.remove('active', 'done');
                if (i < step) {
                    circle.classList.add('done');
                    label.classList.add('done');
                } else if (i === step) {
                    circle.classList.add('active');
                    label.classList.add('active');
                }
                if (i > 1) {
                    const line = document.getElementById('l' + (i - 1));
                    line.classList.toggle('done', i <= step);
                }
            }
        }

        function showError(msg) {
            const txt = document.getElementById('alertErrorText');
            if (txt) txt.textContent = msg;
            const el = document.getElementById('alertError');
            if (el) {
                el.classList.add('error');
                el.style.display = 'flex';
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            const succ = document.getElementById('alertSuccess');
            if (succ) {
                succ.classList.remove('success');
                succ.style.display = 'none';
            }
        }

        function showSuccess(msg) {
            const txt = document.getElementById('alertSuccessText');
            if (txt) txt.textContent = msg;
            const el = document.getElementById('alertSuccess');
            if (el) {
                el.classList.add('success');
                el.style.display = 'flex';
            }
            const err = document.getElementById('alertError');
            if (err) {
                err.classList.remove('error');
                err.style.display = 'none';
            }
        }

        function hideAlerts() {
            const err = document.getElementById('alertError');
            if (err) { err.classList.remove('error'); err.style.display = 'none'; }
            const succ = document.getElementById('alertSuccess');
            if (succ) { succ.classList.remove('success'); succ.style.display = 'none'; }
        }

        function resetWizard() {
            formData = {};
            availablePages = [];
            document.getElementById('f_nama').value = '';
            document.getElementById('f_email').value = '';
            document.getElementById('f_no_telepon').value = '';
            document.getElementById('f_name_wifi').value = '';
            document.getElementById('f_password_wifi').value = '';
            document.getElementById('wifi_box').style.display = 'none';
            document.getElementById('paket_price_box').style.display = 'none';
            const payCard = document.getElementById('payment_info_card');
            if (payCard) payCard.style.display = 'none';
            const payUpload = document.getElementById('payment_upload_group');
            if (payUpload) { payUpload.style.display = 'none'; document.getElementById('f_bukti_bayar').value = ''; }
            const paySection = document.getElementById('payment_section');
            if (paySection) paySection.style.display = 'none';

            // Reset new components
            const paketGrid = document.getElementById('paket-radio-grid');
            const paketPlaceholder = document.getElementById('paket-placeholder');
            if (paketGrid) { paketGrid.innerHTML = ''; }
            if (paketPlaceholder) {
                paketPlaceholder.innerHTML = '<span>Memuat data paket...</span>';
                paketGrid.appendChild(paketPlaceholder);
            }
            document.getElementById('f_paket').value = '';
            document.getElementById('f_price').value = '';
            document.querySelectorAll('.payment-pill').forEach(function(p) { p.classList.remove('active'); });
            document.querySelectorAll('.paket-radio-card').forEach(function(c) { c.classList.remove('selected'); });

            // Reset dropzone
            var dropzone = document.getElementById('dropzone-upload');
            var dropzoneContent = document.getElementById('dropzone-content');
            var dropzonePreview = document.getElementById('dropzone-preview');
            if (dropzone) dropzone.classList.remove('has-file');
            if (dropzoneContent) dropzoneContent.style.display = 'flex';
            if (dropzonePreview) dropzonePreview.style.display = 'none';

            // Reset Select2 drop-downs
            if (window.jQuery && jQuery.fn.select2) {
                $('#f_tipe_layanan').val(null).trigger('change.select2');
                $('#f_kampung').val(null).trigger('change.select2');
                $('#f_desa').val(null).trigger('change.select2');
            } else {
                document.getElementById('f_tipe_layanan').value = '';
                document.getElementById('f_kampung').value = '';
                document.getElementById('f_desa').value = '';
            }

            const fPages = document.getElementById('f_pages');
            if (fPages) fPages.value = '';

            const pGrid = document.getElementById('pages-radio-grid');
            const pPlaceholder = document.getElementById('pages-radio-placeholder');
            if (pGrid) { pGrid.innerHTML = ''; pGrid.style.display = 'none'; }
            if (pPlaceholder) {
                pPlaceholder.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg><span>Pilih <strong>Kampung</strong> dan <strong>Desa</strong> terlebih dahulu untuk melihat pilihan halaman wilayah</span>';
                pPlaceholder.style.display = 'flex';
            }

            if (signaturePad) signaturePad.clear();
            goStep(1);
        }

        document.addEventListener('DOMContentLoaded', function() {
            window.goStep = goStep;
            window.resetWizard = resetWizard;

            // Step 1: Checkbox enable button
            const agreeCheck = document.getElementById('agreeCheck');
            const btnStep1Next = document.getElementById('btnStep1Next');
            if (agreeCheck && btnStep1Next) {
                agreeCheck.addEventListener('change', function() {
                    btnStep1Next.disabled = !this.checked;
                });
                btnStep1Next.addEventListener('click', function() {
                    if (agreeCheck.checked) goStep(2);
                });
            }

            // Toggle WiFi Password Visibility
            const btnToggleWifiPass = document.getElementById('btnToggleWifiPass');
            const fPasswordWifi = document.getElementById('f_password_wifi');
            if (btnToggleWifiPass && fPasswordWifi) {
                btnToggleWifiPass.addEventListener('click', function() {
                    const isPass = fPasswordWifi.getAttribute('type') === 'password';
                    fPasswordWifi.setAttribute('type', isPass ? 'text' : 'password');
                    document.getElementById('eyeIcon').innerHTML = isPass 
                        ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
                        : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
                });
            }

            // Check if Tipe Layanan is PPPoE
            const fTipeLayanan = document.getElementById('f_tipe_layanan');
            const wifiBox = document.getElementById('wifi_box');

            function isPPPoESelected() {
                if (!fTipeLayanan || fTipeLayanan.selectedIndex < 0) return false;
                const text = fTipeLayanan.options[fTipeLayanan.selectedIndex].text || '';
                return text.toLowerCase().includes('pppoe');
            }

            function updatePaketVisibility() {
                // Paket selection is always shown when paket_price_box is visible.
                // No PPPoE dependency — all service types require a package.
                // This function kept for backward compatibility.
            }

            function handleTipeLayananChange() {
                if (isPPPoESelected()) {
                    wifiBox.style.display = 'block';
                } else {
                    wifiBox.style.display = 'none';
                    document.getElementById('f_name_wifi').value = '';
                    document.getElementById('f_password_wifi').value = '';
                }
                updatePaketVisibility();

                // Re-populate paket/payment when tipe layanan changes and a page is already selected
                const currentPageId = fPages ? fPages.value : '';
                if (currentPageId) {
                    const pageObj = availablePages.find(p => p.id === parseInt(currentPageId));
                    if (pageObj) {
                        populatePaketAndPrice(pageObj);
                    }
                }
            }

            if (fTipeLayanan) {
                fTipeLayanan.addEventListener('change', handleTipeLayananChange);
            }

            // ── Select2 Initialization ──────────────────────────────────
            if (window.jQuery && jQuery.fn.select2) {
                $('#f_tipe_layanan').select2({
                    placeholder: '-- Pilih Tipe Layanan --',
                    allowClear: true,
                    minimumResultsForSearch: Infinity
                });
                $('#f_kampung').select2({
                    placeholder: '-- Pilih Kampung --',
                    allowClear: true
                });
                $('#f_desa').select2({
                    placeholder: '-- Pilih Desa --',
                    allowClear: true
                });
                // f_paket and f_price are now radio cards and pill toggles — no Select2 needed

                // Trigger PPPoE box check on tipe layanan change (direct call, no event dispatch to avoid recursion)
                $('#f_tipe_layanan').on('change', handleTipeLayananChange);
            }

            // Payment Pills - click handling
            const paymentInfoCard = document.getElementById('payment_info_card');
            const paymentInfoText = document.getElementById('payment_info_text');
            const paymentUploadGroup = document.getElementById('payment_upload_group');
            const paymentAccountDisplay = document.getElementById('payment_account_display');
            const accountNumberText = document.getElementById('account_number_text');
            const fPrice = document.getElementById('f_price');

            function updatePaymentInfo(btn) {
                if (!btn || !paymentInfoCard || !paymentInfoText) return;
                var desc = btn.getAttribute('data-description') || '';
                var useBukti = btn.getAttribute('data-use-bukti-bayar') || '0';
                var priceId = btn.value;

                // Update hidden input
                if (fPrice) fPrice.value = priceId;

                // Toggle active pill
                document.querySelectorAll('.payment-pill').forEach(function(p) { p.classList.remove('active'); });
                btn.classList.add('active');

                // Show info card if description exists
                if (desc && desc.trim()) {
                    paymentInfoText.textContent = desc;
                    paymentInfoCard.style.display = 'block';

                    // Show account number display area with copy button
                    if (paymentAccountDisplay && accountNumberText) {
                        accountNumberText.textContent = desc;
                        paymentAccountDisplay.style.display = 'block';
                    }
                } else {
                    paymentInfoCard.style.display = 'none';
                    if (paymentAccountDisplay) paymentAccountDisplay.style.display = 'none';
                }

                // Show/hide upload based on use_bukti_bayar
                if (useBukti === '1') {
                    if (paymentUploadGroup) paymentUploadGroup.style.display = 'block';
                } else {
                    if (paymentUploadGroup) {
                        paymentUploadGroup.style.display = 'none';
                        document.getElementById('f_bukti_bayar').value = '';
                        resetDropzone();
                    }
                }
            }

            // Attach click handlers to payment pills
            document.querySelectorAll('.payment-pill').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    updatePaymentInfo(this);
                });
            });

            // Copy account number button
            var copyBtn = document.getElementById('copyAccountBtn');
            if (copyBtn) {
                copyBtn.addEventListener('click', function() {
                    var textToCopy = accountNumberText ? accountNumberText.textContent : '';
                    if (!textToCopy) return;

                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(textToCopy).then(function() {
                            showCopyFeedback(copyBtn);
                        }).catch(function() {
                            fallbackCopy(textToCopy, copyBtn);
                        });
                    } else {
                        fallbackCopy(textToCopy, copyBtn);
                    }
                });

                function showCopyFeedback(el) {
                    var origHtml = el.innerHTML;
                    el.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
                    el.classList.add('copied');
                    setTimeout(function() {
                        el.innerHTML = origHtml;
                        el.classList.remove('copied');
                    }, 1800);
                }

                function fallbackCopy(text, el) {
                    var textarea = document.createElement('textarea');
                    textarea.value = text;
                    textarea.style.position = 'fixed';
                    textarea.style.opacity = '0';
                    document.body.appendChild(textarea);
                    textarea.select();
                    try {
                        document.execCommand('copy');
                        showCopyFeedback(el);
                    } catch (e) {}
                    document.body.removeChild(textarea);
                }
            }

            // Dropzone Upload
            var dropzone = document.getElementById('dropzone-upload');
            var dropzoneContent = document.getElementById('dropzone-content');
            var dropzonePreview = document.getElementById('dropzone-preview');
            var fileInput = document.getElementById('f_bukti_bayar');
            var fileNameDisplay = document.getElementById('file-name-display');
            var fileSizeDisplay = document.getElementById('file-size-display');
            var removeFileBtn = document.getElementById('btn-remove-file');

            function resetDropzone() {
                if (fileInput) fileInput.value = '';
                if (dropzone) dropzone.classList.remove('has-file');
                if (dropzoneContent) dropzoneContent.style.display = 'flex';
                if (dropzonePreview) dropzonePreview.style.display = 'none';
            }

            function handleFileSelect(file) {
                if (!file) return;
                // Validate size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    showError('Ukuran file maksimal 2MB. Silakan pilih file yang lebih kecil.');
                    resetDropzone();
                    return;
                }
                if (fileNameDisplay) fileNameDisplay.textContent = file.name;
                if (fileSizeDisplay) fileSizeDisplay.textContent = (file.size / 1024).toFixed(1) + ' KB';
                if (dropzone) dropzone.classList.add('has-file');
                if (dropzoneContent) dropzoneContent.style.display = 'none';
                if (dropzonePreview) dropzonePreview.style.display = 'flex';
            }

            if (dropzone) {
                // Click to open file picker
                dropzone.addEventListener('click', function(e) {
                    if (e.target === removeFileBtn || removeFileBtn.contains(e.target)) return;
                    if (fileInput) fileInput.click();
                });

                // File input change
                if (fileInput) {
                    fileInput.addEventListener('change', function() {
                        if (this.files && this.files[0]) {
                            handleFileSelect(this.files[0]);
                        }
                    });
                }

                // Drag and drop
                dropzone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('dragover');
                });
                dropzone.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('dragover');
                });
                dropzone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('dragover');
                    var files = e.dataTransfer.files;
                    if (files && files.length > 0) {
                        fileInput.files = files;
                        handleFileSelect(files[0]);
                    }
                });
            }

            // Remove file button
            if (removeFileBtn) {
                removeFileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    resetDropzone();
                });
            }

            // Step 2: Load Pages based on Kampung + Desa (Radio Cards)
            const fKampung = document.getElementById('f_kampung');
            const fDesa    = document.getElementById('f_desa');
            const fPages   = document.getElementById('f_pages');
            const pagesGrid        = document.getElementById('pages-radio-grid');
            const pagesPlaceholder = document.getElementById('pages-radio-placeholder');

            function clearPagesRadio() {
                if (pagesGrid) pagesGrid.innerHTML = '';
                if (pagesGrid) pagesGrid.style.display = 'none';
                if (pagesPlaceholder) pagesPlaceholder.style.display = 'flex';
                if (fPages) fPages.value = '';
                // Also reset paket and payment sections
                var pBox = document.getElementById('paket_price_box');
                if (pBox) pBox.style.display = 'none';
                var paketGrid = document.getElementById('paket-radio-grid');
                if (paketGrid) { paketGrid.innerHTML = ''; }
                document.getElementById('f_paket').value = '';
                document.getElementById('f_price').value = '';
                var paySection = document.getElementById('payment_section');
                if (paySection) paySection.style.display = 'none';
                document.querySelectorAll('.payment-pill').forEach(function(p) { p.classList.remove('active'); });
                document.querySelectorAll('.paket-radio-card').forEach(function(c) { c.classList.remove('selected'); });
            }

            function renderPagesRadio(items) {
                pagesGrid.innerHTML = '';
                if (!items || items.length === 0) {
                    pagesPlaceholder.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span>Tidak ada halaman wilayah untuk kombinasi Desa & Kampung ini.</span>';
                    pagesPlaceholder.style.display = 'flex';
                    pagesGrid.style.display = 'none';
                    return;
                }
                pagesPlaceholder.style.display = 'none';
                pagesGrid.style.display = 'grid';
                items.forEach(function(p, idx) {
                    var id = 'radio_page_' + p.id;
                    var card = document.createElement('label');
                    card.className = 'page-radio-card';
                    card.setAttribute('for', id);
                    card.innerHTML =
                        '<input type="radio" id="' + id + '" name="f_pages_radio" value="' + p.id + '" data-label="' + p.name + '">' +
                        '<span class="page-radio-icon">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>' +
                        '</span>' +
                        '<span class="page-radio-name">' + p.name + '</span>' +
                        '<span class="page-radio-check"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>';
                    pagesGrid.appendChild(card);

                    // Animate in with stagger
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(12px)';
                    setTimeout(function() {
                        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, idx * 60);
                });

                // Listen radio change -> update hidden input
                pagesGrid.querySelectorAll('input[name="f_pages_radio"]').forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        fPages.value = this.value;
                        // Highlight selected card
                        pagesGrid.querySelectorAll('.page-radio-card').forEach(function(c) { c.classList.remove('selected'); });
                        this.closest('.page-radio-card').classList.add('selected');

                        // Populate paket and price based on selected page id
                        const selectedPageId = parseInt(this.value);
                        const pageObj = availablePages.find(p => p.id === selectedPageId);
                        populatePaketAndPrice(pageObj);
                    });
                });
            }

            function populatePaketAndPrice(pageObj) {
                const pBox = document.getElementById('paket_price_box');
                const paketSection = document.getElementById('paket_section');
                const paketGrid = document.getElementById('paket-radio-grid');
                const fPaket = document.getElementById('f_paket');
                const paySection = document.getElementById('payment_section');
                const payCard = document.getElementById('payment_info_card');
                const payUpload = document.getElementById('payment_upload_group');

                if (!pageObj || !pBox || !paketGrid) return;

                // Reset
                if (paySection) paySection.style.display = 'none';
                if (payCard) payCard.style.display = 'none';
                if (payUpload) payUpload.style.display = 'none';
                if (fPaket) fPaket.value = '';
                document.querySelectorAll('.payment-pill').forEach(function(p) { p.classList.remove('active'); });
                document.getElementById('f_price').value = '';

                // Remove only cards, preserve or recreate placeholder
                paketGrid.querySelectorAll('.paket-radio-card').forEach(function(c) { c.remove(); });
                let paketPlaceholder = document.getElementById('paket-placeholder');
                if (!paketPlaceholder) {
                    paketPlaceholder = document.createElement('div');
                    paketPlaceholder.className = 'paket-placeholder';
                    paketPlaceholder.id = 'paket-placeholder';
                    paketGrid.appendChild(paketPlaceholder);
                }

                if (!pageObj.pakets || pageObj.pakets.length === 0) {
                    paketPlaceholder.innerHTML = '<span>Tidak ada paket tersedia untuk halaman ini.</span>';
                    paketPlaceholder.style.display = '';
                    pBox.style.display = 'block';
                    return;
                }

                const isPppoe = isPPPoESelected();

                if (isPppoe) {
                    // Hide placeholder, show cards
                    paketPlaceholder.style.display = 'none';
                    if (paketSection) paketSection.style.display = 'block';

                    pageObj.pakets.forEach(function(pkt, idx) {
                        var id = 'radio_paket_' + pkt.id;
                        var card = document.createElement('label');
                        card.className = 'paket-radio-card';
                        card.setAttribute('for', id);
                        card.innerHTML =
                            '<input type="radio" id="' + id + '" name="f_paket_radio" value="' + pkt.id + '">' +
                            '<span class="paket-radio-name">' + pkt.name + '</span>' +
                            '<span class="paket-radio-check"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>';
                        paketGrid.appendChild(card);

                        card.style.opacity = '0';
                        card.style.transform = 'translateY(10px)';
                        setTimeout(function() {
                            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, idx * 50);
                    });

                    pBox.style.display = 'block';

                    paketGrid.querySelectorAll('input[name="f_paket_radio"]').forEach(function(radio) {
                        radio.addEventListener('change', function() {
                            fPaket.value = this.value;
                            paketGrid.querySelectorAll('.paket-radio-card').forEach(function(c) {
                                c.classList.remove('selected');
                            });
                            this.closest('.paket-radio-card').classList.add('selected');

                            if (payCard) payCard.style.display = 'none';
                            if (payUpload) payUpload.style.display = 'none';
                            document.getElementById('f_price').value = '';
                            document.querySelectorAll('.payment-pill').forEach(function(p) { p.classList.remove('active'); });

                            if (paySection) paySection.style.display = 'block';

                            setTimeout(function() {
                                paySection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }, 100);
                        });
                    });
                } else {
                    // Non-PPPoE: skip paket selection, go directly to payment
                    if (paketSection) paketSection.style.display = 'none';
                    pBox.style.display = 'block';
                    if (paySection) paySection.style.display = 'block';
                }
            }

            function loadPagesRadio() {
                var desaId = $('#f_desa').val();
                var kampungId = $('#f_kampung').val();
                clearPagesRadio();
                document.getElementById('paket_price_box').style.display = 'none';

                if (!desaId) {
                    pagesPlaceholder.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg><span>Pilih <strong>Desa</strong> terlebih dahulu untuk melihat pilihan halaman wilayah</span>';
                    return;
                }

                pagesPlaceholder.innerHTML = '<div class="pages-loading"><span class="pages-spinner"></span><span>Memuat halaman wilayah...</span></div>';
                pagesPlaceholder.style.display = 'flex';

                var payload = { villages_id: desaId };
                if (kampungId) {
                    payload.hometowns_id = kampungId;
                }

                $.ajax({
                    url: '{{ route("public.pendaftaran.get_pages") }}',
                    data: payload,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success' && res.data.length > 0) {
                            availablePages = res.data;
                            renderPagesRadio(res.data);
                        } else {
                            availablePages = [];
                            renderPagesRadio([]);
                        }
                    },
                    error: function() {
                        availablePages = [];
                        renderPagesRadio([]);
                        showError('Gagal memuat data halaman wilayah.');
                    }
                });
            }

            // Register change listeners for Select2 elements
            if (window.jQuery && jQuery.fn.select2) {
                $('#f_desa').on('change', loadPagesRadio);
                $('#f_kampung').on('change', function() {
                    if ($('#f_desa').val()) {
                        loadPagesRadio();
                    }
                });
            }

            // Step 2: Validation & Next
            document.getElementById('btnStep2Next').addEventListener('click', function() {
                const nama = document.getElementById('f_nama').value.trim();
                const telepon = document.getElementById('f_no_telepon').value.trim();
                const tipe = fTipeLayanan ? fTipeLayanan.value : '';
                const kampung = fKampung ? fKampung.value : '';
                const desa = fDesa ? fDesa.value : '';
                const pages = fPages ? fPages.value : '';

                // Reset field errors
                document.querySelectorAll('.form-control-custom').forEach(el => el.classList.remove('is-invalid'));

                if (!nama) {
                    document.getElementById('f_nama').classList.add('is-invalid');
                    return showError('Nama lengkap wajib diisi sesuai KTP.');
                }
                if (!telepon) {
                    document.getElementById('f_no_telepon').classList.add('is-invalid');
                    return showError('Nomor WhatsApp / telepon wajib diisi.');
                }
                if (!tipe) {
                    fTipeLayanan.classList.add('is-invalid');
                    return showError('Silakan pilih tipe layanan internet.');
                }

                const isPppoe = isPPPoESelected();
                const nameWifi = document.getElementById('f_name_wifi').value.trim();
                const passWifi = document.getElementById('f_password_wifi').value.trim();

                if (isPppoe) {
                    if (!nameWifi) {
                        document.getElementById('f_name_wifi').classList.add('is-invalid');
                        return showError('Nama WiFi (SSID) wajib diisi untuk tipe layanan PPPoE.');
                    }
                    if (!passWifi) {
                        document.getElementById('f_password_wifi').classList.add('is-invalid');
                        return showError('Password WiFi wajib diisi untuk tipe layanan PPPoE.');
                    }
                    if (passWifi.length < 4) {
                        document.getElementById('f_password_wifi').classList.add('is-invalid');
                        return showError('Password WiFi minimal 4 karakter.');
                    }
                }

                if (!kampung) {
                    fKampung.classList.add('is-invalid');
                    return showError('Silakan pilih kampung lokasi pemasangan.');
                }
                if (!desa) {
                    fDesa.classList.add('is-invalid');
                    return showError('Silakan pilih desa lokasi pemasangan.');
                }
                if (!pages) {
                    document.getElementById('pages-radio-wrapper').classList.add('is-invalid-group');
                    return showError('Silakan pilih data halaman / wilayah.');
                }
                document.getElementById('pages-radio-wrapper').classList.remove('is-invalid-group');

                const paketHidden = document.getElementById('f_paket');
                const priceHidden = document.getElementById('f_price');
                const paketVal = paketHidden ? paketHidden.value : '';
                const priceVal = priceHidden ? priceHidden.value : '';

                if (isPppoe) {
                    if (!paketVal) {
                        var paketGrid = document.getElementById('paket-radio-grid');
                        if (paketGrid) paketGrid.style.border = '2px solid var(--danger)';
                        return showError('Silakan pilih paket layanan.');
                    }
                    var paketGrid = document.getElementById('paket-radio-grid');
                    if (paketGrid) paketGrid.style.border = '';
                }

                if (!priceVal) {
                    var pillsContainer = document.getElementById('payment-pills-container');
                    if (pillsContainer) pillsContainer.style.border = '2px solid var(--danger)';
                    return showError('Silakan pilih tipe pembayaran.');
                }
                var pillsContainer = document.getElementById('payment-pills-container');
                if (pillsContainer) pillsContainer.style.border = '';

                formData = {
                    nama: nama,
                    email: document.getElementById('f_email').value.trim(),
                    no_telepon: telepon,
                    tipe_layanan_id: tipe,
                    name_wifi: isPppoe ? nameWifi : '',
                    password_wifi: isPppoe ? passWifi : '',
                    hometowns_id: kampung,
                    villages_id: desa,
                    pages_id: pages,
                    paket_id: paketVal,
                    price_id: priceVal
                };

                function selectedText(id) {
                    const sel = document.getElementById(id);
                    if (!sel || sel.selectedIndex < 0) return '-';
                    return sel.options[sel.selectedIndex].text;
                }

                // Get selected paket radio label
                const selectedPaketRadio = document.querySelector('input[name="f_paket_radio"]:checked');
                const selectedPaketLabel = selectedPaketRadio ? selectedPaketRadio.closest('.paket-radio-card').querySelector('.paket-radio-name').textContent : '-';

                // Get selected payment pill label
                const selectedPill = document.querySelector('.payment-pill.active');
                const selectedPillLabel = selectedPill ? selectedPill.textContent.trim() : '-';

                // Get selected page radio label
                const selectedPageRadio = document.querySelector('input[name="f_pages_radio"]:checked');
                const selectedPageLabel = selectedPageRadio ? selectedPageRadio.getAttribute('data-label') : '-';

                let summaryHtml = 
                    '<div class="summary-item"><span class="summary-lbl">Nama Lengkap</span><span class="summary-val">' + formData.nama + '</span></div>' +
                    '<div class="summary-item"><span class="summary-lbl">No. Telepon / WA</span><span class="summary-val">' + formData.no_telepon + '</span></div>' +
                    '<div class="summary-item"><span class="summary-lbl">Email</span><span class="summary-val">' + (formData.email || '-') + '</span></div>' +
                    '<div class="summary-item"><span class="summary-lbl">Tipe Layanan</span><span class="summary-val" style="color:var(--primary);font-weight:700;">' + selectedText('f_tipe_layanan') + '</span></div>';

                if (isPppoe) {
                    summaryHtml += 
                        '<div class="summary-item"><span class="summary-lbl">Nama WiFi (SSID)</span><span class="summary-val wifi-highlight-val">' + formData.name_wifi + '</span></div>' +
                        '<div class="summary-item"><span class="summary-lbl">Password WiFi</span><span class="summary-val wifi-highlight-val">' + formData.password_wifi + '</span></div>';
                }

                summaryHtml += 
                    '<div class="summary-item"><span class="summary-lbl">Kampung</span><span class="summary-val">' + selectedText('f_kampung') + '</span></div>' +
                    '<div class="summary-item"><span class="summary-lbl">Desa</span><span class="summary-val">' + selectedText('f_desa') + '</span></div>' +
                    '<div class="summary-item"><span class="summary-lbl">Halaman Wilayah</span><span class="summary-val">' + selectedPageLabel + '</span></div>' +
                    (isPppoe ? '<div class="summary-item"><span class="summary-lbl">Tipe Paket</span><span class="summary-val" style="color:var(--primary-dark);font-weight:700;">' + selectedPaketLabel + '</span></div>' : '') +
                    '<div class="summary-item"><span class="summary-lbl">Tipe Pembayaran / Harga</span><span class="summary-val">' + selectedPillLabel + '</span></div>';

                document.getElementById('summaryData').innerHTML = summaryHtml;

                goStep(3);
            });

            // Signature Pad Initialization
            const canvas = document.getElementById('signature-pad');
            if (canvas) {
                function initSignaturePad() {
                    var ratio = Math.max(window.devicePixelRatio || 1, 1);
                    var rect = canvas.getBoundingClientRect();
                    var w = rect.width || canvas.offsetWidth || 400;
                    var h = canvas.offsetHeight || 200;

                    // Destroy existing instance first
                    if (signaturePad) {
                        signaturePad.off();
                        signaturePad = null;
                    }

                    // Set canvas internal resolution
                    canvas.width = w * ratio;
                    canvas.height = h * ratio;
                    var ctx = canvas.getContext('2d');
                    ctx.scale(ratio, ratio);

                    signaturePad = new SignaturePad(canvas, {
                        penColor: '#0f172a',
                        minWidth: 1,
                        maxWidth: 3,
                        velocityFilterWeight: 0.7,
                    });

                    // Ensure pointer capture works for touchpad (PointerEvents)
                    canvas.addEventListener('pointerdown', function(e) {
                        canvas.setPointerCapture(e.pointerId);
                    }, { passive: false });
                }

                // Wait a tick so the canvas is rendered before measuring
                setTimeout(initSignaturePad, 50);

                // Reinitialize on resize so mouse/trackpad events remain bound
                var resizeTimer;
                window.addEventListener('resize', function() {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(initSignaturePad, 200);
                });

                document.getElementById('btnResetSignature').addEventListener('click', function() {
                    if (signaturePad) signaturePad.clear();
                });
            }

            // Submit
            document.getElementById('btnSubmit').addEventListener('click', function() {
                if (!signaturePad || signaturePad.isEmpty()) {
                    return showError('Silakan tanda tangani formulir pendaftaran pada kotak di atas terlebih dahulu.');
                }

                var btn = this;
                var ttdBase64 = signaturePad.toDataURL();
                btn.disabled = true;
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="spin"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg> Mengirim Pendaftaran...';

                var fd = new FormData();
                fd.append('_token', '{{ csrf_token() }}');
                fd.append('submission_token', document.getElementById('submission_token').value);
                fd.append('nama', formData.nama);
                fd.append('email', formData.email);
                fd.append('no_telepon', formData.no_telepon);
                fd.append('tipe_layanan_id', formData.tipe_layanan_id);
                fd.append('name_wifi', formData.name_wifi);
                fd.append('password_wifi', formData.password_wifi);
                fd.append('hometowns_id', formData.hometowns_id);
                fd.append('villages_id', formData.villages_id);
                fd.append('pages_id', formData.pages_id);
                fd.append('paket_id', formData.paket_id);
                fd.append('price_id', formData.price_id);
                fd.append('persetujuan_id', document.getElementById('persetujuanId').value);
                fd.append('tanda_tangan_customer', ttdBase64);
                var buktiInput = document.getElementById('f_bukti_bayar');
                if (buktiInput && buktiInput.files && buktiInput.files[0]) {
                    fd.append('bukti_pembayaran', buktiInput.files[0]);
                }

                $.ajax({
                    url: '{{ route("public.pendaftaran.store") }}',
                    method: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            document.getElementById('kodePendaftaran').textContent = res.data.kode;
                            document.getElementById('btnDownloadPdf').href = '{{ route("public.pendaftaran.download", "_kode_") }}'.replace('_kode_', res.data.kode);
                            if (res.data.new_token) {
                                document.getElementById('submission_token').value = res.data.new_token;
                            }
                            
                            // Generate dynamic interactive HTML preview
                            const tName = $('#f_tipe_layanan option:selected').text();
                            const kName = $('#f_kampung option:selected').text();
                            const dName = $('#f_desa option:selected').text();
                            const pName = document.querySelector('input[name="f_pages_radio"]:checked')?.getAttribute('data-label') || '-';
                            const selectedPaketRadio = document.querySelector('input[name="f_paket_radio"]:checked');
                            const paketName = selectedPaketRadio ? (selectedPaketRadio.closest('.paket-radio-card').querySelector('.paket-radio-name').textContent) : '-';
                            const selectedPill = document.querySelector('.payment-pill.active');
                            const priceName = selectedPill ? selectedPill.textContent.trim() : '-';
                            const dateStr = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }) + ' ' + new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                            
                            let wifiRows = '';
                            if (formData.name_wifi) {
                                wifiRows += `<tr><td>Nama WiFi (SSID)</td><td><span class="wifi-highlight-val">${formData.name_wifi}</span></td></tr>`;
                            }
                            if (formData.password_wifi) {
                                wifiRows += `<tr><td>Password WiFi</td><td><span class="wifi-highlight-val">${formData.password_wifi}</span></td></tr>`;
                            }

                            const agreementContent = document.querySelector('.agreement-box')?.innerHTML || '';

                            const previewHtml = `
                                <div class="preview-header">
                                    <div class="preview-header-title">CIO Network — Surat Perjanjian Berlangganan</div>
                                    <div class="preview-header-code">${res.data.kode}</div>
                                </div>
                                <div class="preview-section">
                                    <h4>Data Pelanggan</h4>
                                    <table class="preview-table">
                                        <tr><td>Nama Lengkap</td><td>${formData.nama}</td></tr>
                                        <tr><td>No. Telepon / WA</td><td>${formData.no_telepon}</td></tr>
                                        <tr><td>Email</td><td>${formData.email || '-'}</td></tr>
                                        <tr><td>Tipe Layanan</td><td>${tName}</td></tr>
                                        ${wifiRows}
                                        <tr><td>Kampung</td><td>${kName}</td></tr>
                                        <tr><td>Desa</td><td>${dName}</td></tr>
                                        <tr><td>Halaman Wilayah</td><td>${pName}</td></tr>
                                        <tr><td>Tipe Paket</td><td>${paketName}</td></tr>
                                        <tr><td>Tipe Pembayaran / Harga</td><td>${priceName}</td></tr>
                                        <tr><td>Tanggal Daftar</td><td>${dateStr}</td></tr>
                                    </table>
                                </div>
                                <div class="preview-section">
                                    <h4>Isi Ketentuan Layanan</h4>
                                    <div class="preview-body-content">
                                        ${agreementContent}
                                    </div>
                                </div>
                                <div class="preview-signatures">
                                    <div class="preview-sig-box">
                                        <img src="${ttdBase64}" class="preview-sig-img" alt="Tanda Tangan Pelanggan">
                                        <p><strong>${formData.nama}</strong></p>
                                        <p>Pelanggan</p>
                                    </div>
                                    <div class="preview-sig-box">
                                        <img src="{{ asset('signatures/admin_ttd.png') }}" class="preview-sig-img" alt="Tanda Tangan Admin">
                                        <p><strong>CIO Network</strong></p>
                                        <p>Penyedia Layanan</p>
                                    </div>
                                </div>
                            `;

                            document.getElementById('documentPreview').innerHTML = previewHtml;
                            goStep(4);
                        } else {
                            showError(res.message || 'Terjadi kesalahan saat memproses pendaftaran.');
                        }
                    },
                    error: function(xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Gagal mengirim data pendaftaran. Silakan periksa koneksi internet Anda.';
                        showError(msg);
                    },
                    complete: function() {
                        btn.disabled = false;
                        btn.innerHTML = 'Kirim Pendaftaran <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
                    }
                });
            });
        });
    })();
</script>
@endpush
