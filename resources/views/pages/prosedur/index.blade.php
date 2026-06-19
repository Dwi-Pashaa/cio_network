@extends('layouts.app-pages')

@section('title', 'Prosedur Operasional — CIO Network')

@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background: radial-gradient(circle at 10% 20%, rgba(30, 41, 59, 0.05) 0%, rgba(37, 99, 235, 0.05) 90%), #f8fafc;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            display: block !important;
            padding: 0 0 5rem 0 !important;
        }

        .page-container {
            display: flex;
            gap: 2rem;
            width: 100%;
            max-width: 1250px;
            margin: 3.5rem auto 8rem;
            padding: 0 1.5rem;
            box-sizing: border-box;
        }

        .ambient-orb-1 {
            position: absolute;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.06) 0%, transparent 70%);
            top: -150px;
            left: -150px;
            z-index: -1;
        }

        .ambient-orb-2 {
            position: absolute;
            width: 550px;
            height: 550px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.06) 0%, transparent 70%);
            bottom: -200px;
            right: -150px;
            z-index: -1;
        }

        /* ── SIDEBAR NAV ── */
        .sop-sidebar {
            width: 290px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .sop-nav-card {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.04);
            border-radius: 20px;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .sop-nav-title {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            margin-bottom: 0.5rem;
            padding-left: 0.5rem;
        }

        .sop-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            color: #475569;
            text-decoration: none !important;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
            cursor: pointer;
        }

        .sop-nav-item:hover {
            background: #f8fafc;
            color: #2563eb;
            transform: translateX(4px);
        }

        .sop-nav-item.active {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.08) 0%, rgba(59, 130, 246, 0.03) 100%);
            border-color: rgba(37, 99, 235, 0.15);
            color: #2563eb;
            font-weight: 700;
        }

        .sop-nav-item svg {
            flex-shrink: 0;
            color: #94a3b8;
            transition: color 0.2s;
        }

        .sop-nav-item:hover svg,
        .sop-nav-item.active svg {
            color: #2563eb;
        }

        /* ── CONTENT AREA ── */
        .sop-content-card {
            flex-grow: 1;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.06), 0 0 0 1px rgba(15, 23, 42, 0.03);
            border-radius: 24px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .sop-content-section {
            display: block;
            animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sop-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #2563eb 100%);
            padding: 3rem 2.5rem;
            color: #ffffff;
        }

        .sop-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #60a5fa;
            background: rgba(96, 165, 250, 0.15);
            padding: 6px 12px;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .sop-header h1 {
            color: #ffffff;
            font-size: 1.85rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.3;
            margin: 0 0 1rem 0;
        }

        .sop-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            margin: 0;
            font-weight: 500;
            line-height: 1.6;
            max-width: 680px;
        }

        .sop-body {
            padding: 2.5rem 2.5rem 4rem 2.5rem;
        }

        .sop-section-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sop-section-title svg {
            color: #2563eb;
        }

        /* ── HORIZONTAL STEPPER WIZARD ── */
        .horizontal-stepper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2.5rem;
            position: relative;
            padding: 0 1.5rem;
        }

        .h-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.65rem;
            position: relative;
            z-index: 2;
            width: 120px;
        }

        .h-step-node {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid #cbd5e1;
            color: #64748b;
            font-weight: 800;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .h-step-label {
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            text-align: center;
            transition: color 0.3s;
        }

        .h-step.active .h-step-node {
            border-color: #2563eb;
            color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .h-step.active .h-step-label {
            color: #2563eb;
        }

        .h-step.completed .h-step-node {
            background: #22c55e;
            border-color: #22c55e;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
        }

        .h-step.completed .h-step-label {
            color: #1e293b;
        }

        .h-step-line {
            height: 2px;
            background: #e2e8f0;
            flex-grow: 1;
            margin: 0 -1.5rem;
            position: relative;
            top: -15px; /* aligns line vertically with step node centers */
            z-index: 1;
            transition: background 0.3s;
        }

        .h-step-line.completed {
            background: #22c55e;
        }

        .wizard-pane {
            display: none;
        }

        .wizard-pane.active {
            display: block;
            animation: fadeIn 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .pane-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.5rem;
            margin-top: 0;
        }

        .pane-desc {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 1.75rem;
            margin-top: 0;
        }

        .wizard-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.75rem;
            border-top: 1px solid #f1f5f9;
            padding-top: 1.5rem;
        }

        .btn-sop-back {
            padding: 0.85rem 1.5rem;
            font-size: 0.925rem;
            font-weight: 700;
            color: #475569;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-sop-back:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #94a3b8;
        }

        /* UPLOAD DRAG-DROP ZONE */
        .upload-dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .upload-dropzone:hover {
            border-color: #3b82f6;
            background: #f0f7ff;
        }

        .upload-icon {
            color: #94a3b8;
            margin-bottom: 0.75rem;
            display: inline-flex;
        }

        .upload-dropzone:hover .upload-icon {
            color: #2563eb;
        }

        .upload-text {
            font-size: 0.85rem;
            color: #475569;
            margin: 0;
            font-weight: 600;
        }

        .upload-subtext {
            font-size: 0.75rem;
            color: #94a3b8;
            margin: 0.25rem 0 0 0;
        }

        .preview-image-container {
            display: none;
            margin-top: 0.5rem;
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .preview-image-container img {
            width: 100%;
            height: auto;
            max-height: 220px;
            object-fit: cover;
            display: block;
        }

        /* SUCCESS STATE CARD */
        .success-card {
            display: none;
            text-align: center;
            padding: 3.5rem 2.5rem;
            animation: fadeIn 0.5s ease;
        }

        /* ── CUSTOM FORM STYLES ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .form-group-full {
            grid-column: span 2;
        }

        .form-label-custom {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            margin-bottom: 0.6rem;
            display: block;
        }

        .input-group-custom {
            position: relative;
        }

        .search-bar-container {
            position: relative;
            display: flex;
            gap: 0.75rem;
        }

        .input-icon-wrapper {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .form-control-custom {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.75rem;
            font-size: 0.925rem;
            font-weight: 500;
            background: rgba(248, 250, 252, 0.8);
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            color: #0f172a;
            transition: all 0.2s ease-in-out;
            outline: none;
            box-sizing: border-box;
        }

        .form-control-custom:focus {
            background: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .form-control-custom:focus + .input-icon-wrapper {
            color: #3b82f6;
        }

        .btn-sop-submit {
            padding: 0.9rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            border-radius: 14px;
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.3);
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-sop-submit:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 15px 25px -5px rgba(37, 99, 235, 0.4);
            transform: translateY(-1px);
        }

        .alert-error {
            background: rgba(254, 226, 226, 0.7);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #b91c1c;
            padding: 0.85rem 1rem;
            border-radius: 14px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: none;
            align-items: center;
            gap: 8px;
            animation: slideIn 0.3s ease-out;
        }

        /* Premium Mobile SOP Dropdown Selector */
        .mobile-sop-selector {
            display: none;
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .mobile-select-wrapper {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .mobile-select-label {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            margin-bottom: 0.5rem;
            display: block;
            padding-left: 0.25rem;
        }

        .mobile-select-inner {
            position: relative;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
        }

        .mobile-select-inner:focus-within {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .mobile-select-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #2563eb;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-select-input {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 100%;
            background: transparent;
            border: none;
            outline: none;
            padding: 1.1rem 3.5rem 1.1rem 3.25rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            font-family: inherit;
            cursor: pointer;
        }

        .mobile-select-chevron {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #2563eb;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.98); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE MEDIA QUERIES ── */
        @media (max-width: 900px) {
            .page-container {
                flex-direction: column;
                margin-top: 1.5rem;
                margin-bottom: 6rem !important;
                padding: 0 1rem !important;
                gap: 0;
            }
            .sop-sidebar {
                width: 100%;
            }
            .sop-nav-card {
                display: none;
            }
            .mobile-sop-selector {
                display: block;
            }
            .sop-content-card {
                border-radius: 20px;
            }
            .sop-header {
                padding: 2.25rem 1.75rem;
            }
            .sop-body {
                padding: 1.75rem;
            }
        }

        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-group-full {
                grid-column: span 1;
            }
        }

        @media (max-width: 576px) {
            .horizontal-stepper {
                margin-bottom: 1.75rem;
                padding: 0;
            }
            .h-step {
                width: 80px;
                gap: 0.45rem;
            }
            .h-step-label {
                font-size: 0.65rem;
            }
            .h-step-node {
                width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }
            .h-step-line {
                top: -12px;
            }
            .pane-title {
                font-size: 1.05rem;
            }
            .pane-desc {
                font-size: 0.8rem;
                margin-bottom: 1.25rem;
            }
            .wizard-actions {
                flex-direction: column-reverse;
                gap: 0.75rem;
                padding-top: 1.25rem;
            }
            .btn-sop-back, .btn-sop-submit {
                width: 100% !important;
            }
            .search-bar-container {
                flex-direction: column;
                gap: 0.75rem;
            }
            .form-grid {
                gap: 0.85rem;
                margin-bottom: 1rem;
            }
            .upload-dropzone {
                padding: 1.25rem;
            }
            .upload-text {
                font-size: 0.8rem;
            }
            .upload-subtext {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 480px) {
            .sop-header h1 {
                font-size: 1.45rem;
            }
            .sop-header p {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 400px) {
            .wizard-pane div[style*="display: flex; justify-content: space-between"] {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 0.25rem;
            }
            .wizard-pane div[style*="display: flex; justify-content: space-between"] span:last-child {
                text-align: left !important;
                max-width: 100% !important;
            }
        }

        /* ── MODAL DIALOG STYLE ── */
        .sop-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            box-sizing: border-box;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease-out;
        }

        .sop-modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .sop-modal-card {
            background: #ffffff;
            border-radius: 24px;
            max-width: 420px;
            width: 100%;
            padding: 2.25rem 2rem;
            box-sizing: border-box;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.15);
            text-align: center;
            transform: scale(0.9);
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .sop-modal-overlay.active .sop-modal-card {
            transform: scale(1);
        }

        .sop-modal-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            margin-left: auto;
            margin-right: auto;
        }

        .sop-modal-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.5rem 0;
        }

        .sop-modal-desc {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.6;
            margin: 0 0 1.75rem 0;
        }

        .sop-modal-actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn-modal-cancel {
            flex: 1;
            padding: 0.85rem;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            color: #475569;
            font-size: 0.925rem;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            outline: none;
        }

        .btn-modal-cancel:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #94a3b8;
        }

        .btn-modal-confirm {
            flex: 1;
            padding: 0.85rem;
            border: none;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
            font-size: 0.925rem;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
            transition: all 0.2s;
            outline: none;
        }

        .btn-modal-confirm:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
        }

        /* ── SELECTION DASHBOARD ── */
        .sop-selection-card {
            background: #ffffff;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .sop-selection-title {
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            margin-bottom: 0.5rem;
            padding-left: 0.25rem;
        }

        .sop-selection-grid {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .sop-selection-item {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 1.25rem 1.5rem;
            border-radius: 18px;
            background: #f8fafc;
            border: 1.5px solid rgba(15, 23, 42, 0.05);
            text-decoration: none !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .sop-selection-item:hover {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.06) 0%, rgba(59, 130, 246, 0.02) 100%);
            border-color: rgba(37, 99, 235, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -10px rgba(37, 99, 235, 0.12);
        }

        .selection-icon-wrapper {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            transition: all 0.25s;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.02);
            flex-shrink: 0;
        }

        .sop-selection-item:hover .selection-icon-wrapper {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 8px 20px -6px rgba(37, 99, 235, 0.3);
        }

        .selection-content {
            flex-grow: 1;
        }

        .selection-content h3 {
            margin: 0 0 0.2rem 0;
            font-size: 0.95rem;
            font-weight: 700;
            color: #475569;
            transition: color 0.2s;
        }

        .sop-selection-item:hover .selection-content h3 {
            color: #2563eb;
            font-weight: 700;
        }

        .selection-content p {
            margin: 0;
            font-size: 0.825rem;
            color: #64748b;
            line-height: 1.45;
        }

        .selection-arrow {
            color: #cbd5e1;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }

        .sop-selection-item:hover .selection-arrow {
            color: #2563eb;
            transform: translateX(4px);
        }
    </style>
@endpush

@section('content')
    <div class="ambient-orb-1"></div>
    <div class="ambient-orb-2"></div>

    <div class="page-container" style="{{ empty($tipe) ? 'justify-content: center; margin-top: 6rem; margin-bottom: 10rem;' : '' }}">
        <!-- Sidebar Navigation (Desktop) -->
        @if(!empty($tipe))
            <div class="sop-sidebar">
                <div class="sop-nav-card">
                    <div class="sop-nav-title">Prosedur Operasional</div>
                    
                    <a href="{{ route('public.prosedur') }}" class="sop-nav-item {{ empty($tipe) ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>
                        Beranda
                    </a>

                    <a href="{{ route('public.prosedur', ['tipe' => 'onu-router']) }}" class="sop-nav-item {{ $tipe === 'onu-router' ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>
                        Pergantian Perangkat
                    </a>
                    
                    <a href="{{ route('public.prosedur', ['tipe' => 'pergantian-layanan']) }}" class="sop-nav-item {{ $tipe === 'pergantian-layanan' ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                        Pergantian Layanan
                    </a>
                    
                    <a href="{{ route('public.prosedur', ['tipe' => 'pemutusan']) }}" class="sop-nav-item {{ $tipe === 'pemutusan' ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg>
                        Pemutusan Pelanggan
                    </a>
                </div>
            </div>
        @endif

        <!-- Mobile Selector (Dropdown Menu) -->
        @if(!empty($tipe))
            <div class="mobile-sop-selector">
                <div class="mobile-select-wrapper">
                    <label for="mobile-sop-select" class="mobile-select-label">Pilih Prosedur Operasional</label>
                    <div class="mobile-select-inner">
                        <div class="mobile-select-icon">
                            @if($tipe === 'onu-router')
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>
                            @elseif($tipe === 'pergantian-layanan')
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                            @elseif($tipe === 'pemutusan')
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg>
                            @endif
                        </div>
                        <select id="mobile-sop-select" class="mobile-select-input">
                            <option value="onu-router" {{ $tipe === 'onu-router' ? 'selected' : '' }}>Pergantian Perangkat</option>
                            <option value="pergantian-layanan" {{ $tipe === 'pergantian-layanan' ? 'selected' : '' }}>Pergantian Layanan</option>
                            <option value="pemutusan" {{ $tipe === 'pemutusan' ? 'selected' : '' }}>Pemutusan Pelanggan</option>
                        </select>
                        <div class="mobile-select-chevron">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Content Card -->
        <div class="sop-content-card" style="{{ empty($tipe) ? 'max-width: 800px; width: 100%;' : '' }}">
            @if(empty($tipe))
                <!-- Header -->
                <div class="sop-header">
                    <div class="sop-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        Dashboard SOP
                    </div>
                    <h1>Prosedur Operasional</h1>
                    <p>Silakan pilih salah satu prosedur operasional di bawah untuk memulai proses administrasi dan teknis.</p>
                </div>
                
                <!-- Body selection list -->
                <div class="sop-body">
                    <div class="sop-selection-card">
                        <div class="sop-selection-title">Prosedur Operasional</div>
                        <div class="sop-selection-grid">
                            <a href="{{ route('public.prosedur', ['tipe' => 'onu-router']) }}" class="sop-selection-item">
                                <div class="selection-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>
                                </div>
                                <div class="selection-content">
                                    <h3>Pergantian Perangkat</h3>
                                    <p>Prosedur pergantian ONT/ONU atau Router pelanggan dengan deteksi jaringan otomatis.</p>
                                </div>
                                <div class="selection-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>

                            <a href="{{ route('public.prosedur', ['tipe' => 'pergantian-layanan']) }}" class="sop-selection-item">
                                <div class="selection-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                </div>
                                <div class="selection-content">
                                    <h3>Pergantian Layanan</h3>
                                    <p>Prosedur migrasi paket layanan pelanggan antara Voucher Hotspot dan PPPoE.</p>
                                </div>
                                <div class="selection-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>

                            <a href="{{ route('public.prosedur', ['tipe' => 'pemutusan']) }}" class="sop-selection-item">
                                <div class="selection-icon-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg>
                                </div>
                                <div class="selection-content">
                                    <h3>Pemutusan Pelanggan</h3>
                                    <p>Prosedur penutupan layanan atau pemutusan langganan secara resmi.</p>
                                </div>
                                <div class="selection-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                @include('pages.prosedur.' . $tipe)
            @endif
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#mobile-sop-select').on('change', function() {
                const tipe = $(this).val();
                window.location.href = "{{ route('public.prosedur') }}?tipe=" + tipe;
            });
        });
    </script>
@endpush
