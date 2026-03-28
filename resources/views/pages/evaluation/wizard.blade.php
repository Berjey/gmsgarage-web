@extends('layouts.app')

@section('title', 'Aracımı Değerle - ' . ($settings['site_title'] ?? 'GMSGARAGE'))
@section('description', 'Aracınızın değerini öğrenin. Hızlı ve güvenilir araç değerleme hizmeti.')

@push('styles')
<style>
    .eval-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .eval-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .dark .eval-card {
        background: #252525;
    }

    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .dark .form-label {
        color: #d1d5db;
    }

    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        background: white;
        color: #111827;
        transition: border-color 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #dc2626;
    }

    .dark .form-input {
        background: #2a2a2a;
        border-color: #4b5563;
        color: #e5e7eb;
    }

    /* ===== CUSTOM DROPDOWN STYLES - KURUMSAL KİMLİK ===== */
    .eval-custom-dropdown {
        position: relative;
        z-index: 10;
    }

    .eval-custom-dropdown.dropdown-open {
        z-index: 999999 !important;
    }

    /* Light Mode Trigger */
    .eval-custom-dropdown-trigger {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        border-radius: 12px;
        background: white;
        border: 2px solid #e5e7eb;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .eval-custom-dropdown-trigger::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(220, 38, 38, 0.05), transparent);
        transition: left 0.5s ease;
    }
    
    .eval-custom-dropdown-trigger:hover::before {
        left: 100%;
    }

    .eval-custom-dropdown-trigger:hover {
        border-color: #dc2626;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
    }

    .eval-custom-dropdown.dropdown-open .eval-custom-dropdown-trigger {
        border-color: #dc2626;
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.2), 0 8px 16px rgba(220, 38, 38, 0.2);
        background: linear-gradient(135deg, #ffffff 0%, #fef2f2 100%);
    }

    .eval-custom-dropdown-trigger .selected-text {
        flex: 1;
        text-align: left;
        font-weight: 600;
        color: #111827;
    }

    .eval-custom-dropdown-trigger .selected-text.placeholder {
        color: #9ca3af;
        font-weight: 500;
    }

    .eval-custom-dropdown-trigger .arrow {
        transition: transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        color: #dc2626;
    }

    .eval-custom-dropdown.dropdown-open .arrow {
        transform: rotate(180deg) scale(1.1);
    }

    /* Light Mode Panel */
    .eval-custom-dropdown-panel {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        margin-top: 8px;
        background-color: #ffffff;
        opacity: 0;
        visibility: hidden;
        z-index: 999999;
        border-radius: 12px;
        border: 2px solid rgba(220, 38, 38, 0.2);
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15), 0 10px 20px -5px rgba(220, 38, 38, 0.1);
        transform: translateY(-15px) scale(0.95);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        max-height: 320px;
        overflow-y: auto;
        backdrop-filter: blur(10px);
    }

    .eval-custom-dropdown-panel.open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    /* Custom Scrollbar - Light Mode */
    .eval-custom-dropdown-panel::-webkit-scrollbar {
        width: 8px;
    }

    .eval-custom-dropdown-panel::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 8px;
    }

    .eval-custom-dropdown-panel::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #dc2626, #991b1b);
        border-radius: 8px;
        transition: background 0.3s;
    }

    .eval-custom-dropdown-panel::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #ef4444, #b91c1c);
    }

    /* Light Mode Options */
    .eval-custom-dropdown-option {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 14px 16px;
        margin: 4px 8px;
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
        font-weight: 500;
        font-size: 15px;
        color: #1f2937;
        position: relative;
        overflow: hidden;
    }
    
    .eval-custom-dropdown-option::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #dc2626, #b91c1c);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .eval-custom-dropdown-option:hover::before {
        transform: scaleY(1);
    }

    .eval-custom-dropdown-option:hover {
        background: linear-gradient(90deg, rgba(220, 38, 38, 0.1) 0%, rgba(220, 38, 38, 0.05) 50%, transparent 100%);
        color: #dc2626;
        padding-left: 20px;
    }

    .eval-custom-dropdown-option.selected {
        background: linear-gradient(90deg, rgba(220, 38, 38, 0.15) 0%, rgba(220, 38, 38, 0.08) 50%, transparent 100%);
        color: #dc2626;
        font-weight: 700;
        border-left: 4px solid #dc2626;
        padding-left: calc(16px + 4px);
    }
    
    .eval-custom-dropdown-option.selected::after {
        content: '✓';
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #dc2626;
        font-weight: bold;
        font-size: 18px;
    }

    /* ========================================
       DARK MODE - KURUMSAL KİMLİK TASARIMI
       ======================================== */
    
    /* Dark Mode Trigger */
    .dark .eval-custom-dropdown-trigger {
        background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
        border-color: #4b5563;
        color: #f9fafb;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 
                    inset 0 1px 0 rgba(255, 255, 255, 0.05);
    }
    
    .dark .eval-custom-dropdown-trigger::before {
        background: linear-gradient(90deg, transparent, rgba(220, 38, 38, 0.15), transparent);
    }
    
    .dark .eval-custom-dropdown-trigger:hover {
        border-color: #dc2626;
        box-shadow: 0 8px 16px -4px rgba(220, 38, 38, 0.3),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1);
        background: linear-gradient(135deg, #242424 0%, #2f2f2f 100%);
    }
    
    .dark .eval-custom-dropdown.dropdown-open .eval-custom-dropdown-trigger {
        border-color: #dc2626;
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.3),
                    0 12px 24px -8px rgba(220, 38, 38, 0.4),
                    inset 0 2px 4px rgba(0, 0, 0, 0.3),
                    inset 0 -1px 0 rgba(220, 38, 38, 0.2);
        background: linear-gradient(135deg, #2a2a2a 0%, #1f1f1f 100%);
    }

    .dark .eval-custom-dropdown-trigger .selected-text {
        color: #f9fafb;
    }

    .dark .eval-custom-dropdown-trigger .selected-text.placeholder {
        color: #6b7280;
    }
    
    .dark .eval-custom-dropdown-trigger .arrow {
        color: #fca5a5;
        filter: drop-shadow(0 0 4px rgba(220, 38, 38, 0.3));
    }

    /* Dark Mode Panel */
    .dark .eval-custom-dropdown-panel {
        background: linear-gradient(180deg, #1f1f1f 0%, #1a1a1a 100%);
        border-color: #4b5563;
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.6),
                    0 0 0 1px rgba(220, 38, 38, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
    }

    /* Dark Mode Scrollbar */
    .dark .eval-custom-dropdown-panel::-webkit-scrollbar-track {
        background: #111827;
        border-radius: 8px;
    }

    .dark .eval-custom-dropdown-panel::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #dc2626, #991b1b);
        box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    }

    .dark .eval-custom-dropdown-panel::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #ef4444, #b91c1c);
        box-shadow: 0 0 8px rgba(220, 38, 38, 0.5);
    }

    /* Dark Mode Options */
    .dark .eval-custom-dropdown-option {
        color: #e5e7eb;
    }
    
    .dark .eval-custom-dropdown-option::before {
        background: linear-gradient(180deg, #dc2626, #7f1d1d);
        box-shadow: 0 0 10px rgba(220, 38, 38, 0.5);
    }

    .dark .eval-custom-dropdown-option:hover {
        background: linear-gradient(90deg, 
                    rgba(220, 38, 38, 0.15) 0%, 
                    rgba(220, 38, 38, 0.05) 50%,
                    transparent 100%);
        color: #fca5a5;
        box-shadow: inset 0 0 20px rgba(220, 38, 38, 0.1);
    }

    .dark .eval-custom-dropdown-option.selected {
        background: linear-gradient(90deg, 
                    rgba(220, 38, 38, 0.25) 0%, 
                    rgba(220, 38, 38, 0.15) 50%,
                    rgba(220, 38, 38, 0.05) 100%);
        color: #fca5a5;
        font-weight: 700;
        border-left: 4px solid #dc2626;
        box-shadow: inset 0 0 30px rgba(220, 38, 38, 0.2),
                    inset 4px 0 0 #dc2626;
    }
    
    .dark .eval-custom-dropdown-option.selected::after {
        color: #fca5a5;
        text-shadow: 0 0 8px rgba(220, 38, 38, 0.6);
    }

    /* Disabled State */
    .eval-custom-dropdown.disabled {
        opacity: 0.5;
        pointer-events: none;
    }

    .eval-custom-dropdown.disabled .eval-custom-dropdown-trigger {
        cursor: not-allowed;
        background-color: #f3f4f6;
    }

    .dark .eval-custom-dropdown.disabled .eval-custom-dropdown-trigger {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    }

    .wizard-step { display: none; }
    .wizard-step.active { display: block; }

    .step-dots {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-bottom: 1.5rem;
    }
    .step-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #d1d5db;
    }
    .step-dot.active { background: #dc2626; }
    .dark .step-dot { background: #4b5563; }
    .dark .step-dot.active { background: #dc2626; }

    .btn-back {
        width: 100%;
        padding: 12px 24px;
        background: #f3f4f6;
        color: #374151;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }
    .btn-back:hover { background: #e5e7eb; }
    .dark .btn-back { background: #374151; color: #e5e7eb; }

    .buttons-row { display: flex; gap: 1rem; }
    .buttons-row > * { flex: 1; }

    /* Ekspertiz Layout */
    .ekspertiz-layout {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 2rem;
        margin-bottom: 1.5rem;
        align-items: start;
    }
    @media (max-width: 640px) {
        .ekspertiz-layout {
            grid-template-columns: 1fr;
        }
        .ekspertiz-car-wrapper {
            order: -1;
        }
    }

    .ekspertiz-table-wrapper {
        overflow-x: auto;
    }

    .damage-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .damage-table th, .damage-table td {
        padding: 8px 6px;
        border: 1px solid #e5e7eb;
        text-align: center;
    }
    .damage-table th {
        background: #f9fafb;
        font-weight: 600;
        font-size: 11px;
        color: #6b7280;
    }
    .damage-table td:first-child {
        text-align: left;
        font-weight: 500;
        padding-left: 10px;
        color: #374151;
    }
    .damage-table tbody tr {
        transition: background 0.15s ease;
        cursor: pointer;
    }
    .damage-table tbody tr:hover {
        background: #f3f4f6;
    }
    .dark .damage-table th, .dark .damage-table td { border-color: #4b5563; }
    .dark .damage-table th { background: #374151; color: #9ca3af; }
    .dark .damage-table td { color: #e5e7eb; }
    .dark .damage-table td:first-child { color: #e5e7eb; }
    .dark .damage-table tbody tr:hover { background: #374151; }

    .ekspertiz-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #3b82f6;
    }

    /* Car Diagram */
    .ekspertiz-car-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        padding-top: 1rem;
    }
    .car-diagram {
        width: 100%;
        max-width: 320px;
    }
    .car-diagram svg {
        width: 100%;
        height: auto;
    }
    .car-part {
        transition: fill 0.2s ease;
        cursor: pointer;
    }
    .car-part:hover {
        opacity: 0.8;
    }

    /* Tooltip */
    .car-tooltip {
        position: absolute;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        padding: 12px;
        z-index: 1000;
        min-width: 140px;
        display: none;
    }
    .dark .car-tooltip {
        background: #2a2a2a;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .car-tooltip.active {
        display: block;
    }
    .car-tooltip-title {
        font-weight: 600;
        font-size: 13px;
        color: #111827;
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e5e7eb;
    }
    .dark .car-tooltip-title {
        color: #f3f4f6;
        border-color: #4b5563;
    }
    .car-tooltip-option {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 0;
        cursor: pointer;
        font-size: 13px;
        color: #374151;
        transition: color 0.15s;
    }
    .dark .car-tooltip-option {
        color: #d1d5db;
    }
    .car-tooltip-option:hover {
        color: #111827;
    }
    .dark .car-tooltip-option:hover {
        color: #fff;
    }
    .car-tooltip-dot {
        width: 16px;
        height: 16px;
        border-radius: 3px;
        border: 2px solid #d1d5db;
        flex-shrink: 0;
    }
    .car-tooltip-dot.dot-orijinal {
        background: #fff;
        border-color: #d1d5db;
    }
    .car-tooltip-dot.dot-boyali {
        background: #3b82f6;
        border-color: #3b82f6;
    }
    .car-tooltip-dot.dot-lokal-boyali {
        background: #fbbf24;
        border-color: #fbbf24;
    }
    .car-tooltip-dot.dot-degismis {
        background: #dc2626;
        border-color: #dc2626;
    }
    .car-tooltip-option.selected .car-tooltip-dot {
        box-shadow: 0 0 0 2px #3b82f6;
    }
    .car-legend {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
        font-size: 12px;
        color: #6b7280;
    }
    .dark .car-legend { color: #9ca3af; }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    .legend-boyali { background: #fbbf24; }
    .legend-degismis { background: #dc2626; }

    /* Tramer Row */
    .tramer-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }
    .dark .tramer-row { border-color: #4b5563; }
    @media (max-width: 480px) {
        .tramer-row {
            grid-template-columns: 1fr;
        }
    }

    .tutar-input-wrapper {
        position: relative;
    }
    .tutar-input-wrapper .form-input {
        padding-right: 40px;
    }
    .tutar-suffix {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 14px;
    }

    .form-select:disabled, .form-input:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f3f4f6;
    }

    .dark .form-select, .dark .form-input {
        background: #2a2a2a;
        border-color: #4b5563;
        color: #e5e7eb;
    }

    .dark .form-select:disabled, .dark .form-input:disabled {
        background: #1f2937;
    }

    .btn-submit {
        width: 100%;
        padding: 12px 24px;
        background: #dc2626;
        color: white;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-submit:hover {
        background: #b91c1c;
    }

    .btn-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<section class="bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 text-white py-8">
    <div class="container-custom">
        <h1 class="text-2xl md:text-3xl font-bold mb-1">Aracınızı Değerleyin</h1>
        <p class="text-primary-100">Araç bilgilerinizi girin</p>
    </div>
</section>

<div class="eval-container">
    <div class="eval-card">
        <!-- Step Dots -->
        <div class="step-dots">
            <div class="step-dot active" id="dot-1" onclick="goToStep(1)" style="cursor: pointer;"></div>
            <div class="step-dot" id="dot-2" onclick="goToStep(2)" style="cursor: pointer;"></div>
            <div class="step-dot" id="dot-3" style="cursor: pointer;"></div>
        </div>

        <form id="evaluation-form" method="POST" action="{{ route('evaluation.submit') }}">
            @csrf

            <!-- STEP 1: Araç Bilgileri -->
            <div class="wizard-step active" id="step-1">

            <!-- Row 1: Marka | Yıl -->
            <div class="grid grid-cols-2 gap-4 mb-4" style="position: relative; z-index: 50;">
                <div>
                    <label class="form-label">Marka *</label>
                    <div class="eval-custom-dropdown" id="marka-dropdown">
                        <button type="button" class="eval-custom-dropdown-trigger" data-value="" data-id="">
                            <span class="selected-text placeholder">Marka Seçin</span>
                            <svg class="arrow w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="eval-custom-dropdown-panel"></div>
                    </div>
                    <input type="hidden" name="marka" id="marka-input" value="" required>
                    <input type="hidden" name="marka_id" id="marka-id" value="{{ request('marka_id') }}">
                    <span id="marka-error" class="hidden mt-1 flex items-center gap-1 text-xs text-red-500"><svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg><span></span></span>
                </div>
                <div>
                    <label class="form-label">Yıl *</label>
                    <div class="eval-custom-dropdown disabled" id="yil-dropdown">
                        <button type="button" class="eval-custom-dropdown-trigger" data-value="">
                            <span class="selected-text placeholder">Yıl Seçin</span>
                            <svg class="arrow w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="eval-custom-dropdown-panel"></div>
                    </div>
                    <input type="hidden" name="yil" id="yil-input" value="" required>
                    <span id="yil-error" class="hidden mt-1 flex items-center gap-1 text-xs text-red-500"><svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg><span></span></span>
                </div>
            </div>

            <!-- Row 2: Model | Gövde Tipi -->
            <div class="grid grid-cols-2 gap-4 mb-4" style="position: relative; z-index: 40;">
                <div>
                    <label class="form-label">Model *</label>
                    <div class="eval-custom-dropdown disabled" id="model-dropdown">
                        <button type="button" class="eval-custom-dropdown-trigger" data-value="" data-id="">
                            <span class="selected-text placeholder">Model Seçin</span>
                            <svg class="arrow w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="eval-custom-dropdown-panel"></div>
                    </div>
                    <input type="hidden" name="model" id="model-input" value="" required>
                    <input type="hidden" name="model_id" id="model-id" value="">
                    <span id="model-error" class="hidden mt-1 flex items-center gap-1 text-xs text-red-500"><svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg><span></span></span>
                </div>
                <div>
                    <label class="form-label">Gövde Tipi</label>
                    <div class="eval-custom-dropdown disabled" id="govde-dropdown">
                        <button type="button" class="eval-custom-dropdown-trigger" data-value="" data-id="">
                            <span class="selected-text placeholder">Gövde Tipi Seçin</span>
                            <svg class="arrow w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="eval-custom-dropdown-panel"></div>
                    </div>
                    <input type="hidden" name="govde_tipi" id="govde-input" value="">
                    <input type="hidden" name="govde_tipi_id" id="govde-id" value="">
                </div>
            </div>

            <!-- Row 3: Yakıt Tipi | Vites Tipi -->
            <div class="grid grid-cols-2 gap-4 mb-4" style="position: relative; z-index: 30;">
                <div>
                    <label class="form-label">Yakıt Tipi</label>
                    <div class="eval-custom-dropdown disabled" id="yakit-dropdown">
                        <button type="button" class="eval-custom-dropdown-trigger" data-value="" data-id="">
                            <span class="selected-text placeholder">Yakıt Tipi Seçin</span>
                            <svg class="arrow w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="eval-custom-dropdown-panel"></div>
                    </div>
                    <input type="hidden" name="yakit_tipi" id="yakit-input" value="">
                    <input type="hidden" name="yakit_tipi_id" id="yakit-id" value="">
                </div>
                <div>
                    <label class="form-label">Vites Tipi</label>
                    <div class="eval-custom-dropdown disabled" id="vites-dropdown">
                        <button type="button" class="eval-custom-dropdown-trigger" data-value="" data-id="">
                            <span class="selected-text placeholder">Vites Tipi Seçin</span>
                            <svg class="arrow w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="eval-custom-dropdown-panel"></div>
                    </div>
                    <input type="hidden" name="vites_tipi" id="vites-input" value="">
                    <input type="hidden" name="vites_tipi_id" id="vites-id" value="">
                </div>
            </div>

            <!-- Row 4: Versiyon (full width) -->
            <div class="mb-4" style="position: relative; z-index: 25;">
                <label class="form-label">Versiyon</label>
                <div class="eval-custom-dropdown disabled" id="versiyon-dropdown">
                    <button type="button" class="eval-custom-dropdown-trigger" data-value="" data-id="">
                        <span class="selected-text placeholder">Versiyon Seçin</span>
                        <svg class="arrow w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="eval-custom-dropdown-panel"></div>
                </div>
                <input type="hidden" name="versiyon" id="versiyon-input" value="">
                <input type="hidden" name="versiyon_id" id="versiyon-id" value="">
            </div>

            <!-- Row 5: Kilometre | Renk -->
            <div class="grid grid-cols-2 gap-4 mb-6" style="position: relative; z-index: 20;">
                <div>
                    <label class="form-label">Kilometre *</label>
                    <input type="text" name="kilometre" id="kilometre-input" class="form-input" placeholder="Kilometre giriniz" disabled required>
                    <span id="kilometre-error" class="hidden mt-1 flex items-center gap-1 text-xs text-red-500"><svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg><span></span></span>
                </div>
                <div style="position: relative; z-index: 30;">
                    <label class="form-label">Renk <span id="renk-label-optional" class="text-gray-400 text-xs font-normal">(isteğe bağlı)</span></label>
                    <div class="eval-custom-dropdown disabled" id="renk-dropdown">
                        <button type="button" class="eval-custom-dropdown-trigger" data-value="" data-id="">
                            <span class="selected-text placeholder">Renk Seçin</span>
                            <svg class="arrow w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="eval-custom-dropdown-panel"></div>
                    </div>
                    <input type="hidden" name="renk" id="renk-input" value="">
                    <input type="hidden" name="renk_id" id="renk-id" value="">
                    <span id="renk-error" class="hidden mt-1 flex items-center gap-1 text-xs text-red-500"><svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg><span></span></span>
                </div>
            </div>

            <!-- Step 1 Button -->
            <button type="button" class="btn-submit" style="position: relative; z-index: 1;" onclick="goToStep2()">
                Devam Et
            </button>
            </div><!-- End Step 1 -->

            <!-- STEP 2: Hasar Bilgileri -->
            <div class="wizard-step" id="step-2">
                <div class="ekspertiz-layout">
                    <!-- Sol: Tablo -->
                    <div class="ekspertiz-table-wrapper">
                        <table class="damage-table">
                            <thead>
                                <tr>
                                    <th style="min-width: 130px;"></th>
                                    <th>Orijinal</th>
                                    <th>Boyalı</th>
                                    <th>Lokal Boyalı</th>
                                    <th>Değişmiş</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $parts = [
                                        'Sağ arka çamurluk' => 'sag_arka_camurluk',
                                        'Arka kaput' => 'arka_kaput',
                                        'Sol arka çamurluk' => 'sol_arka_camurluk',
                                        'Sağ arka kapı' => 'sag_arka_kapi',
                                        'Sağ ön kapı' => 'sag_on_kapi',
                                        'Tavan' => 'tavan',
                                        'Sol arka kapı' => 'sol_arka_kapi',
                                        'Sol ön kapı' => 'sol_on_kapi',
                                        'Sağ ön çamurluk' => 'sag_on_camurluk',
                                        'Motor kaputu' => 'motor_kaputu',
                                        'Sol ön çamurluk' => 'sol_on_camurluk',
                                        'Ön tampon' => 'on_tampon',
                                        'Arka tampon' => 'arka_tampon',
                                    ];
                                @endphp
                                @foreach($parts as $partName => $partKey)
                                    <tr>
                                        <td>{{ $partName }}</td>
                                        <td><input type="checkbox" name="ekspertiz[{{ $partKey }}]" value="ORIJINAL" checked class="ekspertiz-checkbox" data-part="{{ $partKey }}"></td>
                                        <td><input type="checkbox" name="ekspertiz[{{ $partKey }}]" value="BOYALI" class="ekspertiz-checkbox" data-part="{{ $partKey }}"></td>
                                        <td><input type="checkbox" name="ekspertiz[{{ $partKey }}]" value="LOKAL_BOYALI" class="ekspertiz-checkbox" data-part="{{ $partKey }}"></td>
                                        <td><input type="checkbox" name="ekspertiz[{{ $partKey }}]" value="DEGISMIS" class="ekspertiz-checkbox" data-part="{{ $partKey }}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Sağ: Araç Görseli -->
                    <div class="ekspertiz-car-wrapper" style="position: relative;">
                        <!-- Tooltip -->
                        <div class="car-tooltip" id="car-tooltip">
                            <div class="car-tooltip-title" id="tooltip-title">Parça Adı</div>
                            <div class="car-tooltip-option" data-value="ORIJINAL">
                                <span class="car-tooltip-dot dot-orijinal"></span>
                                <span>Orijinal</span>
                            </div>
                            <div class="car-tooltip-option" data-value="BOYALI">
                                <span class="car-tooltip-dot dot-boyali"></span>
                                <span>Boyalı</span>
                            </div>
                            <div class="car-tooltip-option" data-value="LOKAL_BOYALI">
                                <span class="car-tooltip-dot dot-lokal-boyali"></span>
                                <span>Lokal Boyalı</span>
                            </div>
                            <div class="car-tooltip-option" data-value="DEGISMIS">
                                <span class="car-tooltip-dot dot-degismis"></span>
                                <span>Değişmiş</span>
                            </div>
                        </div>
                        <div class="car-diagram" id="car-diagram">
                            <svg width="100%" height="350" viewBox="0 0 227 303" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Nakit-Sat" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="DD-Nakitsat-03.1" transform="translate(-1002.000000, -232.000000)"><g transform="translate(1003.000000, 233.000000)"><path d="M94.6557611,63.8442042 C92.939813,65.0732963 91.7141357,66.9764066 91.5098562,69.1967019 L91.0195853,74.5491997 C90.7744499,77.3245689 92.8581012,79.7431049 95.7180147,79.9809937 C98.5779282,80.2188824 101.070139,78.1968277 101.315274,75.4214586 L102.05068,67.3332398 C105.482577,68.6416281 109.118752,69.3156463 112.83664,69.3156463 L127.340487,69.3156463 C130.322968,69.3156463 132.815179,67.0160547 132.978602,64.1217411 C133.632297,50.6017284 134,36.4869936 134,21.8171851 C134,21.6982407 134,21.6189444 134,21.5 C134,6.71124703 133.632297,-7.52243211 132.978602,-21.1217411 C132.856035,-24.0160547 130.363824,-26.3156463 127.340487,-26.3156463 L112.877496,-26.3156463 C109.159608,-26.3156463 105.523432,-25.6416281 102.091536,-24.3332398 L101.35613,-32.4214586 C101.110994,-35.1968277 98.5779282,-37.2188824 95.7588706,-36.9809937 C92.8989571,-36.7431049 90.8153058,-34.2849207 91.0604412,-31.5491997 L91.5507121,-26.1967019 C91.7549917,-23.9764066 92.939813,-22.0732963 94.696617,-20.8442042 L94.696617,63.8442042 L94.6557611,63.8442042 Z" stroke="#D3D2D2" fill="#F0F0F0" transform="translate(112.500000, 21.500000) rotate(-90.000000) translate(-112.500000, -21.500000) "></path> <path d="M98,60.0833333 C101.017241,61.3211806 104.195402,62 107.454023,62 L120.166667,62 C122.781609,62 124.954023,59.8038194 125.074713,57.0086806 C125.637931,43.9913194 126,30.4149306 126,16.3194444 C126,16.2395833 126,16.1197917 126,16 C126,1.78472222 125.678161,-11.9114583 125.074713,-25.0086806 C124.954023,-27.8038194 122.781609,-30 120.166667,-30 L107.454023,-30 C104.195402,-30 101.017241,-29.3611111 98,-28.0833333 L98,60.0833333 Z" id="svg-on_tampon" fill="#FFFFFF" fill-rule="nonzero" title="Ön tampon" transform="translate(112.000000, 16.000000) rotate(-90.000000) translate(-112.000000, -16.000000) " class="car-part"><title>Ön tampon</title></path> <path d="M209.370189,124.39232 C207.52386,121.065414 204.674093,118.460007 201.182124,116.936845 C199.014696,115.974848 196.646579,115.49385 194.278462,115.49385 L151.010162,115.49385 C150.970024,115.293434 150.929887,115.173184 150.889749,115.093018 C150.408098,113.529773 148.762458,108.359041 144.668426,106.555297 C143.183336,105.873883 142.059484,105.994132 141.818658,106.034216 C141.658108,106.034216 140.694806,106.154465 140.213156,106.555297 C138.48724,107.958209 142.139759,112.968609 144.146637,115.49385 L89.0778914,115.49385 C87.3519759,115.49385 85.5859228,115.293434 83.9001449,114.852519 C82.2143669,114.411604 80.4483139,114.211188 78.7223983,114.211188 L37.501114,114.211188 C35.1329973,114.211188 32.8050183,114.652103 30.5974519,115.49385 C22.7304882,118.540173 15.0240748,123.310073 13.4587095,131.607295 C11.8532067,139.9446 11.5722437,148.72282 11.5722437,156.979958 C11.5722437,163.914351 11.7729316,171.16941 12.7763708,178.264136 L9.36467738,178.264136 C8.88302654,178.264136 8.04013757,178.264136 8,180.94971 C8,183.595201 9.04357682,183.635284 10.6892172,183.635284 L13.6995349,183.635284 C15.7866886,191.170925 23.0917263,195.62016 30.5573143,198.50615 C32.7648807,199.347897 35.0928598,199.788812 37.4609764,199.788812 L78.6822608,199.788812 C80.4483139,199.788812 82.1742294,199.588396 83.8600073,199.147481 C85.5457852,198.706566 87.3118383,198.50615 89.0377538,198.50615 L144.1065,198.50615 C142.099621,201.031391 138.447103,206.041791 140.173018,207.444703 C140.654669,207.845535 141.617971,207.965784 141.778521,207.965784 C142.019346,208.005868 143.143198,208.126117 144.628288,207.444703 C148.682183,205.640959 150.327823,200.470227 150.849612,198.906982 C150.889749,198.826816 150.929887,198.666483 150.970024,198.50615 L194.238325,198.50615 C196.606441,198.50615 198.974558,198.025152 201.141987,197.063155 C204.593818,195.539993 207.483723,192.934586 209.330051,189.60768 C212.862157,183.154286 217.999766,171.369826 217.999766,156.979958 C218.039904,142.630174 212.902295,130.845714 209.370189,124.39232 Z" id="Shape" stroke="#D3D2D2" fill="#F0F0F0" fill-rule="nonzero" transform="translate(113.000000, 157.000000) rotate(-90.000000) translate(-113.000000, -157.000000) "></path> <path d="M40.1953541,165.054383 L121.473933,165.054383 C121.995722,165.054383 122.116134,165.77588 121.594346,165.936213 L100.441847,172.068942 C98.6757936,172.590024 96.8294654,172.830523 94.9831372,172.830523 L59.5015253,172.830523 C56.6918954,172.830523 53.9224031,172.229275 51.3937362,171.066862 L39.9946663,165.89613 C39.553153,165.695714 39.7137033,165.054383 40.1953541,165.054383 Z" id="Path" stroke="#D3D2D2" fill="#D3D2D2" fill-rule="nonzero" transform="translate(80.833235, 168.942453) rotate(-90.000000) translate(-80.833235, -168.942453) "></path> <path d="M185.83574,172.858799 L104.55716,172.858799 C104.07551,172.858799 103.914959,172.217468 104.356473,171.976969 L115.755542,166.806236 C118.284209,165.643823 121.053702,165.042575 123.863332,165.042575 L159.344943,165.042575 C161.191272,165.042575 163.0376,165.323158 164.803653,165.804156 L185.956152,171.936885 C186.437803,172.097218 186.357528,172.858799 185.83574,172.858799 Z" id="Path" stroke="#D3D2D2" fill="#D3D2D2" fill-rule="nonzero" transform="translate(145.186807, 168.950687) rotate(-90.000000) translate(-145.186807, -168.950687) "></path> <path d="M106.499636,243.096992 C105.014546,234.559271 104.171657,225.01947 104.171657,214.998671 C104.171657,204.977872 105.014546,195.438071 106.499636,186.90035 C106.740462,185.537522 108.185414,184.816024 109.429679,185.377189 L120.668199,190.78842 C121.47095,191.189252 121.952601,192.031 121.832188,192.91283 C120.949162,199.286058 120.427373,206.861782 120.427373,214.958588 C120.427373,223.055394 120.949162,230.631118 121.832188,237.004346 C121.952601,237.886176 121.47095,238.768007 120.668199,239.128756 L109.429679,244.539987 C108.185414,245.221401 106.740462,244.459821 106.499636,243.096992 Z" id="Path" stroke="#D3D2D2" fill="#D3D2D2" fill-rule="nonzero" transform="translate(113.011099, 214.989729) rotate(-90.000000) translate(-113.011099, -214.989729) "></path> <g id="Group-4" transform="translate(162.000000, 52.000000)" stroke="#D3D2D2"><path d="M129.900636,106.906489 C125.403559,103.580153 120.22389,101.375954 114.682849,100.454198 L79.5494364,94.5629771 L62.1634161,84.6641221 C47.6282213,76.3683206 31.1657078,72 14.4221271,72 L-2.16084382,72 C-6.81853055,72.1603053 -12.3194192,72.6412214 -18.3824424,73.9236641 C-18.8642721,74.0438931 -19.3461018,74.1240458 -19.8279314,74.2442748 C-28.7016277,76.2480916 -37.1336468,79.8549618 -44.8830739,84.7041985 C-46.9710024,85.9866412 -49.0187785,87.3091603 -51.106707,88.5916031 C-51.5483842,88.8320611 -52.0703663,88.9522901 -52.552196,88.9522901 L-61.7872645,88.9522901 C-64.3971752,88.9522901 -66.9267809,89.7538168 -69.0548619,91.1965649 C-69.6973015,91.6374046 -69.9382163,92.398855 -69.7374539,93.120229 C-68.4525748,98.0896947 -71.704925,102.898855 -71.5041627,107.868321 C-71.4238577,110.753817 -72.7890418,113.479008 -71.3034003,115.923664 C-70.6609608,116.604962 -69.9783688,117.326336 -69.3359292,118.007634 C-67.8502878,119.570611 -66.6055611,121.293893 -65.6419018,123.217557 C-65.0797672,124.259542 -64.2767178,125.501908 -63.0319911,126.624046 C-60.1811656,129.188931 -56.9689679,129.549618 -55.9250036,129.629771 L-53.5560078,130.110687 C-52.3915861,130.351145 -51.3074694,129.389313 -51.3877743,128.227099 C-51.4279268,127.706107 -51.4680792,127.185115 -51.4680792,126.624046 C-51.4680792,116.604962 -43.276975,108.469466 -33.1987045,108.549618 C-23.1605866,108.629771 -15.0899397,117.246183 -15.2103972,127.265267 C-15.2505496,129.269084 -15.3308546,131.112595 -15.6119219,132.916031 C-15.7725318,133.998092 -14.9293299,135 -13.8050606,135 L74.6106823,135 C75.6546466,135 76.4978485,134.118321 76.4175436,133.076336 C76.2569337,130.992366 76.2167812,128.98855 76.2167812,126.664122 C76.2167812,116.725191 84.2472756,108.669847 94.1649361,108.589695 C104.564426,108.509542 112.755531,117.326336 112.434311,127.666031 C112.394158,129.509542 112.193396,131.232824 112.032786,132.916031 C111.912329,134.038168 112.835836,134.959924 113.960105,134.879771 L129.900636,133.998092 C131.667345,133.917939 132.912071,132.314885 132.631004,130.591603 L132.309784,128.708015 L134.598475,120.171756 C134.879543,119.169847 135,118.208015 135,117.206107 C135,109.551527 129.900636,106.906489 129.900636,106.906489 Z" id="Shape" fill="#F0F0F0" transform="translate(31.500000, 103.500000) rotate(-90.000000) translate(-31.500000, -103.500000) "></path> <path d="M13.0500284,141.698113 L13.0875754,141.773585 C14.8898297,144.792453 17.1801946,147.471698 19.8835762,149.698113 C21.2728139,150.830189 22.4743168,152.113208 23.4505379,153.471698 C24.426759,154.792453 24.9524165,155.886792 25.515621,157.018868 C26.0037316,157.962264 26.4918421,158.981132 27.2803284,160.226415 C28.0688147,161.433962 28.9323949,162.603774 29.8710691,163.698113 C31.072572,165.09434 32.6119976,165.924528 34.0763292,165.962264 C36.0287715,166 38.732153,166 42.1489269,166 C46.5794688,166 51.0851047,165.962264 53,165.962264 L53,146.113208 C53,140.45283 52.4743425,134.792453 51.4230274,129.245283 L48.9449277,116 L38.2815894,116 C29.7208812,116 21.19772,118.113208 13.6132329,122.075472 L9.93363022,124 C8.13137587,124.943396 6.81723206,126.490566 6.14138668,128.415094 C5.87855792,129.169811 5.99119881,130 6.40421544,130.716981 L13.0500284,141.698113 Z" id="svg-sag_arka_kapi" fill="#FFFFFF" fill-rule="nonzero" title="Sağ arka kapı" transform="translate(29.500000, 141.000000) rotate(-90.000000) translate(-29.500000, -141.000000) " class="car-part"><title>Sağ arka kapı</title></path> <path d="M6.98512508,98.1209373 L6.98512508,118 L52.6260859,118 C53.3028053,118 53.9043336,117.508692 54.0171202,116.82842 L54.2426933,115.505669 C55.671323,106.435374 55.0697947,97.0249433 52.5132994,88.2191988 C51.6110069,85.1579743 49.6184444,82.5502646 46.9115669,80.8873772 C34.3170679,73.2532124 19.9555794,68.8692366 5.33092222,68.1133787 L3,68 L5.40611326,80.8495843 C6.45878781,86.5185185 6.98512508,92.3386243 6.98512508,98.1209373 Z" id="svg-sag_on_kapi" fill="#FFFFFF" fill-rule="nonzero" title="Sağ ön kapı" transform="translate(29.000000, 93.000000) rotate(-90.000000) translate(-29.000000, -93.000000) " class="car-part"><title>Sağ ön kapı</title></path> <path d="M-5.55787695,144.094347 C-4.80693763,142.622649 -3.64298169,141.415102 -2.21619699,140.584913 L0.261902751,139.150951 C6.75752784,135.377366 14.0791862,133.33963 21.5885793,133.188687 L29.62363,133.037743 C30.8251329,133.000008 31.876448,133.905668 32.0266358,135.075479 L33.9415311,147.679253 C34.054172,148.320762 33.5660614,148.8868 32.890216,148.8868 L0.787560273,148.8868 C-1.35261678,148.8868 -3.38015293,148.132083 -5.03221943,146.773592 C-5.78315874,146.132083 -6.00844054,145.000008 -5.55787695,144.094347 Z" id="Path" fill="#D3D2D2" fill-rule="nonzero" transform="translate(14.084337, 140.961703) rotate(-90.000000) translate(-14.084337, -140.961703) "></path> <path d="M-4.12304309,84.2625682 L1.47868934,85.0184261 C14.185975,86.7191064 26.3293278,91.3298396 36.9688599,98.4726967 L37.269624,98.6616612 C37.194433,98.6616612 37.119242,98.6616612 37.0816464,98.6616612 L31.2919364,98.6616612 C29.524947,98.6616612 28.0963172,100.097791 28.0963172,101.874057 L28.0963172,101.91185 C28.0963172,103.499152 29.3745649,104.78411 30.9535767,104.78411 L36.7432868,104.78411 C37.119242,104.78411 37.4951972,104.708524 37.8335568,104.557353 C38.0215344,104.481767 38.2471075,104.557353 38.3222986,104.746317 C38.3974896,104.935282 38.3222986,105.162039 38.134321,105.237625 C37.6831748,105.426589 37.2320285,105.502175 36.7432868,105.502175 L30.9535767,105.502175 C28.9986097,105.502175 27.3820024,103.914874 27.3820024,101.91185 L27.3820024,101.874057 L0.426014786,100.400134 C-0.927423921,100.324549 -2.05528951,99.3797262 -2.39364919,98.0569749 L-5.43888628,85.774284 C-5.70205492,84.9428404 -4.98774004,84.1491896 -4.12304309,84.2625682 Z" id="Path" fill="#D3D2D2" fill-rule="nonzero" transform="translate(16.428437, 94.876946) rotate(-90.000000) translate(-16.428437, -94.876946) "></path> <path d="M13.9131056,168.010844 C13.9131056,168.010844 19.0519095,166.400737 26.9577617,167.205791 L31.4640974,166.964275 C31.4640974,166.964275 39.2118326,160.765362 41.5835882,161.006878 C43.9553439,161.288647 48.066387,167.970592 48.066387,167.970592 L57,184.071663 C56.8814122,183.8704 44.2320487,181.253976 38.3817181,185.238991 C33.3219727,188.700721 29.4085759,194.175086 28.7365784,199.971471 L28.064581,203.996739 C28.064581,203.996739 20.2377873,204.318761 17.7079146,198.562628 C15.1780419,192.806494 12.2133473,190.753608 12.2133473,190.753608 C12.2133473,190.753608 11.4227621,181.978524 12.8853448,179.804879 C14.3083982,177.671487 13.9131056,168.010844 13.9131056,168.010844 Z" id="svg-sag_arka_camurluk" fill="#FFFFFF" fill-rule="nonzero" title="Sağ arka çamurluk" transform="translate(34.500000, 182.500000) rotate(-90.000000) translate(-34.500000, -182.500000) " class="car-part"><title>Sağ arka çamurluk</title></path> <path d="M14.5326799,52 L57.1956592,45.0528587 C57.1956592,45.0528587 69.2385483,41.6170442 70.4922245,38.3700108 C71.7459006,35.1229773 72.3917338,32.9708738 71.7459006,30.592233 C71.1000675,28.2135922 69.4664894,22.5124056 69.4664894,22.5124056 C69.4664894,22.5124056 72.1258025,17.7551241 68.972617,17.7551241 C65.8194316,17.7551241 56.1727324,17 56.1727324,17 C56.1727324,17 58.0345528,41.9848751 35.6936697,42.3173455 C15.1854438,42.6225429 15.7176067,20.2847896 15.7176067,20.2847896 L11,20.2847896 C11,20.2847896 15.5968126,38.1434736 11,52 L14.5326799,52 Z" id="svg-sag_on_camurluk" fill="#FFFFFF" fill-rule="nonzero" title="Sağ ön çamurluk" transform="translate(41.500000, 34.500000) scale(-1, 1) rotate(-90.000000) translate(-41.500000, -34.500000) " class="car-part"><title>Sağ ön çamurluk</title></path></g> <g id="Group-4-Copy" transform="translate(31.525480, 155.500000) scale(-1, 1) translate(-31.525480, -155.500000) translate(0.025480, 52.000000)" stroke="#D3D2D2"><path d="M129.900636,106.906489 C125.403559,103.580153 120.22389,101.375954 114.682849,100.454198 L79.5494364,94.5629771 L62.1634161,84.6641221 C47.6282213,76.3683206 31.1657078,72 14.4221271,72 L-2.16084382,72 C-6.81853055,72.1603053 -12.3194192,72.6412214 -18.3824424,73.9236641 C-18.8642721,74.0438931 -19.3461018,74.1240458 -19.8279314,74.2442748 C-28.7016277,76.2480916 -37.1336468,79.8549618 -44.8830739,84.7041985 C-46.9710024,85.9866412 -49.0187785,87.3091603 -51.106707,88.5916031 C-51.5483842,88.8320611 -52.0703663,88.9522901 -52.552196,88.9522901 L-61.7872645,88.9522901 C-64.3971752,88.9522901 -66.9267809,89.7538168 -69.0548619,91.1965649 C-69.6973015,91.6374046 -69.9382163,92.398855 -69.7374539,93.120229 C-68.4525748,98.0896947 -71.704925,102.898855 -71.5041627,107.868321 C-71.4238577,110.753817 -72.7890418,113.479008 -71.3034003,115.923664 C-70.6609608,116.604962 -69.9783688,117.326336 -69.3359292,118.007634 C-67.8502878,119.570611 -66.6055611,121.293893 -65.6419018,123.217557 C-65.0797672,124.259542 -64.2767178,125.501908 -63.0319911,126.624046 C-60.1811656,129.188931 -56.9689679,129.549618 -55.9250036,129.629771 L-53.5560078,130.110687 C-52.3915861,130.351145 -51.3074694,129.389313 -51.3877743,128.227099 C-51.4279268,127.706107 -51.4680792,127.185115 -51.4680792,126.624046 C-51.4680792,116.604962 -43.276975,108.469466 -33.1987045,108.549618 C-23.1605866,108.629771 -15.0899397,117.246183 -15.2103972,127.265267 C-15.2505496,129.269084 -15.3308546,131.112595 -15.6119219,132.916031 C-15.7725318,133.998092 -14.9293299,135 -13.8050606,135 L74.6106823,135 C75.6546466,135 76.4978485,134.118321 76.4175436,133.076336 C76.2569337,130.992366 76.2167812,128.98855 76.2167812,126.664122 C76.2167812,116.725191 84.2472756,108.669847 94.1649361,108.589695 C104.564426,108.509542 112.755531,117.326336 112.434311,127.666031 C112.394158,129.509542 112.193396,131.232824 112.032786,132.916031 C111.912329,134.038168 112.835836,134.959924 113.960105,134.879771 L129.900636,133.998092 C131.667345,133.917939 132.912071,132.314885 132.631004,130.591603 L132.309784,128.708015 L134.598475,120.171756 C134.879543,119.169847 135,118.208015 135,117.206107 C135,109.551527 129.900636,106.906489 129.900636,106.906489 Z" id="Shape" fill="#F0F0F0" transform="translate(31.500000, 103.500000) rotate(-90.000000) translate(-31.500000, -103.500000) "></path> <path d="M13.0500284,141.698113 L13.0875754,141.773585 C14.8898297,144.792453 17.1801946,147.471698 19.8835762,149.698113 C21.2728139,150.830189 22.4743168,152.113208 23.4505379,153.471698 C24.426759,154.792453 24.9524165,155.886792 25.515621,157.018868 C26.0037316,157.962264 26.4918421,158.981132 27.2803284,160.226415 C28.0688147,161.433962 28.9323949,162.603774 29.8710691,163.698113 C31.072572,165.09434 32.6119976,165.924528 34.0763292,165.962264 C36.0287715,166 38.732153,166 42.1489269,166 C46.5794688,166 51.0851047,165.962264 53,165.962264 L53,146.113208 C53,140.45283 52.4743425,134.792453 51.4230274,129.245283 L48.9449277,116 L38.2815894,116 C29.7208812,116 21.19772,118.113208 13.6132329,122.075472 L9.93363022,124 C8.13137587,124.943396 6.81723206,126.490566 6.14138668,128.415094 C5.87855792,129.169811 5.99119881,130 6.40421544,130.716981 L13.0500284,141.698113 Z" id="svg-sol_arka_kapi" fill="#FFFFFF" fill-rule="nonzero" title="Sol arka kapı" transform="translate(29.500000, 141.000000) rotate(-90.000000) translate(-29.500000, -141.000000) " class="car-part"><title>Sol arka kapı</title></path> <path d="M6.98512508,98.1209373 L6.98512508,118 L52.6260859,118 C53.3028053,118 53.9043336,117.508692 54.0171202,116.82842 L54.2426933,115.505669 C55.671323,106.435374 55.0697947,97.0249433 52.5132994,88.2191988 C51.6110069,85.1579743 49.6184444,82.5502646 46.9115669,80.8873772 C34.3170679,73.2532124 19.9555794,68.8692366 5.33092222,68.1133787 L3,68 L5.40611326,80.8495843 C6.45878781,86.5185185 6.98512508,92.3386243 6.98512508,98.1209373 Z" id="svg-sol_on_kapi" fill="#FFFFFF" fill-rule="nonzero" title="Sol ön kapı" transform="translate(29.000000, 93.000000) rotate(-90.000000) translate(-29.000000, -93.000000) " class="car-part"><title>Sol ön kapı</title></path> <path d="M-5.55787695,144.094347 C-4.80693763,142.622649 -3.64298169,141.415102 -2.21619699,140.584913 L0.261902751,139.150951 C6.75752784,135.377366 14.0791862,133.33963 21.5885793,133.188687 L29.62363,133.037743 C30.8251329,133.000008 31.876448,133.905668 32.0266358,135.075479 L33.9415311,147.679253 C34.054172,148.320762 33.5660614,148.8868 32.890216,148.8868 L0.787560273,148.8868 C-1.35261678,148.8868 -3.38015293,148.132083 -5.03221943,146.773592 C-5.78315874,146.132083 -6.00844054,145.000008 -5.55787695,144.094347 Z" id="Path" fill="#D3D2D2" fill-rule="nonzero" transform="translate(14.084337, 140.961703) rotate(-90.000000) translate(-14.084337, -140.961703) "></path> <path d="M-4.12304309,84.2625682 L1.47868934,85.0184261 C14.185975,86.7191064 26.3293278,91.3298396 36.9688599,98.4726967 L37.269624,98.6616612 C37.194433,98.6616612 37.119242,98.6616612 37.0816464,98.6616612 L31.2919364,98.6616612 C29.524947,98.6616612 28.0963172,100.097791 28.0963172,101.874057 L28.0963172,101.91185 C28.0963172,103.499152 29.3745649,104.78411 30.9535767,104.78411 L36.7432868,104.78411 C37.119242,104.78411 37.4951972,104.708524 37.8335568,104.557353 C38.0215344,104.481767 38.2471075,104.557353 38.3222986,104.746317 C38.3974896,104.935282 38.3222986,105.162039 38.134321,105.237625 C37.6831748,105.426589 37.2320285,105.502175 36.7432868,105.502175 L30.9535767,105.502175 C28.9986097,105.502175 27.3820024,103.914874 27.3820024,101.91185 L27.3820024,101.874057 L0.426014786,100.400134 C-0.927423921,100.324549 -2.05528951,99.3797262 -2.39364919,98.0569749 L-5.43888628,85.774284 C-5.70205492,84.9428404 -4.98774004,84.1491896 -4.12304309,84.2625682 Z" id="Path" fill="#D3D2D2" fill-rule="nonzero" transform="translate(16.428437, 94.876946) rotate(-90.000000) translate(-16.428437, -94.876946) "></path> <path d="M13.9131056,168.010844 C13.9131056,168.010844 19.0519095,166.400737 26.9577617,167.205791 L31.4640974,166.964275 C31.4640974,166.964275 39.2118326,160.765362 41.5835882,161.006878 C43.9553439,161.288647 48.066387,167.970592 48.066387,167.970592 L57,184.071663 C56.8814122,183.8704 44.2320487,181.253976 38.3817181,185.238991 C33.3219727,188.700721 29.4085759,194.175086 28.7365784,199.971471 L28.064581,203.996739 C28.064581,203.996739 20.2377873,204.318761 17.7079146,198.562628 C15.1780419,192.806494 12.2133473,190.753608 12.2133473,190.753608 C12.2133473,190.753608 11.4227621,181.978524 12.8853448,179.804879 C14.3083982,177.671487 13.9131056,168.010844 13.9131056,168.010844 Z" id="svg-sol_arka_camurluk" fill="#FFFFFF" fill-rule="nonzero" title="Sol arka çamurluk" transform="translate(34.500000, 182.500000) rotate(-90.000000) translate(-34.500000, -182.500000) " class="car-part"><title>Sol arka çamurluk</title></path> <path d="M14.5326799,52 L57.1956592,45.0528587 C57.1956592,45.0528587 69.2385483,41.6170442 70.4922245,38.3700108 C71.7459006,35.1229773 72.3917338,32.9708738 71.7459006,30.592233 C71.1000675,28.2135922 69.4664894,22.5124056 69.4664894,22.5124056 C69.4664894,22.5124056 72.1258025,17.7551241 68.972617,17.7551241 C65.8194316,17.7551241 56.1727324,17 56.1727324,17 C56.1727324,17 58.0345528,41.9848751 35.6936697,42.3173455 C15.1854438,42.6225429 15.7176067,20.2847896 15.7176067,20.2847896 L11,20.2847896 C11,20.2847896 15.5968126,38.1434736 11,52 L14.5326799,52 Z" id="svg-sol_on_camurluk" fill="#FFFFFF" fill-rule="nonzero" title="Sol ön çamurluk" transform="translate(41.500000, 34.500000) scale(-1, 1) rotate(-90.000000) translate(-41.500000, -34.500000) " class="car-part"><title>Sol ön çamurluk</title></path></g> <path d="M125.268608,160.858908 C124.706682,162.863068 122.619528,164.02548 120.61265,163.464316 L99.2594625,157.451836 C97.3328592,156.890671 96.1688696,154.966678 96.6103829,153.002601 C98.0553354,146.388874 98.8580868,138.732983 98.8580868,130.556011 C98.8580868,122.379039 98.0553354,114.723149 96.6103829,108.109421 C96.2090072,106.145345 97.3328592,104.221351 99.2594625,103.660186 L120.61265,97.6477069 C122.619528,97.0865422 124.706682,98.2489549 125.268608,100.253115 C127.957825,109.512333 129.442915,119.733548 129.442915,130.556011 C129.442915,141.378474 127.957825,151.639773 125.268608,160.858908 Z" id="Path" stroke="#D3D2D2" fill="#D3D2D2" fill-rule="nonzero" transform="translate(112.979958, 130.556011) rotate(-90.000000) translate(-112.979958, -130.556011) "></path> <g><path d="M83,55 C83,55 94.8944481,86.4 83,122 L125.907825,122 C125.907825,122 142.312584,115.72 140.915585,88.88 C139.518586,62.04 125.907825,55 125.907825,55 L83,55 Z" id="svg-motor_kaputu" stroke="#D3D2D2" fill="#FFFFFF" fill-rule="nonzero" title="Motor kaputu" transform="translate(112.000000, 88.500000) rotate(-90.000000) translate(-112.000000, -88.500000) " class="car-part"><title>Motor kaputu</title></path></g> <g><path d="M126,205.023942 L106.684058,205.023942 C106.684058,205.023942 98,204.012393 98,215.139427 C98,226.26646 98,266.161932 98,266.161932 C98,266.161932 99.3797101,273 104.857971,273 C110.336232,273 126,273 126,273 C126,273 119.101449,243.665094 126,205.023942 Z" id="svg-arka_kaput" stroke="#D3D2D2" fill="#FFFFFF" fill-rule="nonzero" title="Arka kaput" transform="translate(112.000000, 239.000000) rotate(-90.000000) translate(-112.000000, -239.000000) " class="car-part"><title>Arka kaput</title></path></g> <g><path d="M87.1085905,151 C87.1085905,151 78.5117927,172.53629 86.188933,200 L136.890047,200 C136.890047,200 143.887441,175.104839 136.890047,151 L87.1085905,151 Z" id="svg-tavan" stroke="#D3D2D2" fill="#FFFFFF" fill-rule="nonzero" title="Tavan" transform="translate(111.500000, 175.500000) rotate(-90.000000) translate(-111.500000, -175.500000) " class="car-part"><title>Tavan</title></path></g> <path d="M90.9813007,21.7212413 C90.9813007,21.7212413 91.0226055,16.000759 88.7921464,13.000506 C86.5616874,10.000253 78.3420328,7 78.3420328,7 C78.3420328,7 75.9463545,24.2814572 83.8355708,28.9218485 C91.724787,33.5622398 90.9813007,21.7212413 90.9813007,21.7212413 Z" id="Shape" fill="#D3D2D2" fill-rule="nonzero" transform="translate(84.500000, 18.500000) rotate(-90.000000) translate(-84.500000, -18.500000) "></path> <path d="M149.981301,15.2787587 C149.981301,15.2787587 150.022605,20.999241 147.792146,23.999494 C145.561687,26.999747 137.342033,30 137.342033,30 C137.342033,30 134.946355,12.7185428 142.835571,8.0781515 C150.724787,3.43776021 149.981301,15.2787587 149.981301,15.2787587 Z" id="Shape" fill="#D3D2D2" fill-rule="nonzero" transform="translate(143.500000, 18.500000) rotate(-90.000000) translate(-143.500000, -18.500000) "></path> <path d="M127.5,239.49005 C124.170077,238.176617 120.641944,237.5 117.034527,237.5 L102.961637,237.5 C100.067775,237.5 97.6496164,239.808458 97.4910486,242.71393 C96.8567775,256.28607 96.5,270.455224 96.5,285.181592 C96.5,285.300995 96.5,285.380597 96.5,285.5 C96.5,300.345771 96.8567775,314.634328 97.4910486,328.28607 C97.6099744,331.191542 100.028133,333.5 102.961637,333.5 L117.034527,333.5 C120.641944,333.5 124.170077,332.823383 127.5,331.50995 L127.5,239.49005 Z" id="Shape" stroke="#D3D2D2" fill="#D8D8D8" title="Arka tampon" transform="translate(112.000000, 285.500000) rotate(-90.000000) translate(-112.000000, -285.500000) "></path> <path d="M126,241.916667 C122.982759,240.678819 119.804598,240 116.545977,240 L103.833333,240 C101.218391,240 99.045977,242.196181 98.9252874,244.991319 C98.362069,258.008681 98,271.585069 98,285.680556 C98,285.760417 98,285.880208 98,286 C98,300.215278 98.3218391,313.911458 98.9252874,327.008681 C99.045977,329.803819 101.218391,332 103.833333,332 L116.545977,332 C119.804598,332 122.982759,331.361111 126,330.083333 L126,241.916667 Z" id="svg-arka_tampon" fill="#FFFFFF" fill-rule="nonzero" title="Arka tampon" transform="translate(112.000000, 286.000000) rotate(-90.000000) translate(-112.000000, -286.000000) " class="car-part"><title>Arka tampon</title></path> <path d="M90.4887892,298 L87.5112108,298 C86.1479821,298 85,296.735391 85,295.144432 L85,263.855568 C85,262.264609 86.1479821,261 87.5112108,261 L90.4887892,261 C91.8520179,261 93,262.264609 93,263.855568 L93,295.144432 C93,296.735391 91.8520179,298 90.4887892,298 Z" id="Shape" fill="#D3D2D2" fill-rule="nonzero" transform="translate(89.000000, 279.500000) rotate(-90.000000) translate(-89.000000, -279.500000) "></path> <path d="M138.488789,298 L135.511211,298 C134.147982,298 133,296.735391 133,295.144432 L133,263.855568 C133,262.264609 134.147982,261 135.511211,261 L138.488789,261 C139.852018,261 141,262.264609 141,263.855568 L141,295.144432 C141,296.735391 139.852018,298 138.488789,298 Z" id="Shape" fill="#D3D2D2" fill-rule="nonzero" transform="translate(137.000000, 279.500000) rotate(-90.000000) translate(-137.000000, -279.500000) "></path></g></g></g></svg>
                        </div>
                        <div class="car-legend">
                            <span class="legend-item"><span class="legend-dot legend-boyali"></span> Boyalı</span>
                            <span class="legend-item"><span class="legend-dot legend-degismis"></span> Değişmiş</span>
                        </div>
                    </div>
                </div>

                <!-- Tramer Section -->
                <div class="tramer-row">
                    <div class="tramer-select-wrapper">
                        <label class="form-label">Tramer *</label>
                        <div class="eval-custom-dropdown" id="tramer-dropdown">
                            <button type="button" class="eval-custom-dropdown-trigger" data-value="YOK">
                                <span class="selected-text">Yok</span>
                                <svg class="arrow w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="eval-custom-dropdown-panel">
                                <div class="eval-custom-dropdown-option selected" data-value="YOK">Yok</div>
                                <div class="eval-custom-dropdown-option" data-value="VAR">Var</div>
                                <div class="eval-custom-dropdown-option" data-value="BILMIYORUM">Bilmiyorum</div>
                                <div class="eval-custom-dropdown-option" data-value="AGIR_HASAR">Ağır Hasar Kayıtlı</div>
                            </div>
                        </div>
                        <input type="hidden" name="tramer" id="tramer-input" value="YOK" required>
                    </div>
                    <div class="tramer-tutar-wrapper">
                        <label class="form-label" id="tramer-tutari-label">Toplam Tramer Tutarı</label>
                        <div class="tutar-input-wrapper">
                            <input type="text" name="tramer_tutari" id="tramer-tutari" class="form-input" placeholder="0" disabled>
                            <span class="tutar-suffix">TL</span>
                        </div>
                        <span id="tramer-tutari-error" class="hidden mt-1 flex items-center gap-1 text-xs text-red-500"><svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg><span></span></span>
                    </div>
                </div>

                <!-- Step 2 Button -->
                <button type="button" class="btn-submit" style="margin-top: 1.5rem;" onclick="goToStep(3)">Devam Et</button>
            </div><!-- End Step 2 -->

            <!-- STEP 3: İletişim Bilgileri -->
            <div class="wizard-step" id="step-3">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">İletişim Bilgileri</h3>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="form-label">Ad *</label>
                        <input type="text" name="ad" id="ad-input" class="form-input" placeholder="Adınız" required>
                        <span id="ad-error" class="hidden text-xs text-red-500 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </span>
                    </div>
                    <div>
                        <label class="form-label">Soyad *</label>
                        <input type="text" name="soyad" id="soyad-input" class="form-input" placeholder="Soyadınız" required>
                        <span id="soyad-error" class="hidden text-xs text-red-500 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="form-label">Telefon *</label>
                        <input type="tel" name="telefon" id="telefon-input" class="form-input" placeholder="05XX XXX XX XX" required>
                        <span id="telefon-error" class="hidden text-xs text-red-500 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </span>
                    </div>
                    <div>
                        <label class="form-label">E-posta *</label>
                        <input type="email" name="email" id="email-input" class="form-input" placeholder="ornek@email.com" required>
                        <span id="email-error" class="hidden text-xs text-red-500 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Not (Opsiyonel)</label>
                    <textarea name="not" id="not-input" class="form-input" rows="3" placeholder="Eklemek istediğiniz notlar..."></textarea>
                </div>

                {{-- Dinamik Yasal Onaylar --}}
                <div class="mb-6">
                    <x-form-legal-consents formId="evaluation" />
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit" id="submit-btn">
                    <span id="submit-text">Değerleme Talebi Gönder</span>
                    <span id="submit-loading" class="hidden">Gönderiliyor...</span>
                </button>
            </div><!-- End Step 3 -->
        </form>
    </div>
</div>

@push('scripts')
<script>
// ===== GLOBAL INLINE VALIDATION HELPERS =====
function showFieldError(inputEl, errorId, msg) {
    const wrap = document.getElementById(errorId);
    if (!wrap) return;
    const spanEl = wrap.querySelector('span');
    if (spanEl) spanEl.textContent = msg;
    wrap.classList.remove('hidden');
    if (inputEl) {
        if (inputEl.classList.contains('eval-custom-dropdown-trigger')) {
            inputEl.style.borderColor = '#ef4444';
        } else {
            inputEl.classList.add('border-red-500');
        }
    }
}
function clearFieldError(inputEl, errorId) {
    const wrap = document.getElementById(errorId);
    if (wrap) wrap.classList.add('hidden');
    if (inputEl) {
        if (inputEl.classList.contains('eval-custom-dropdown-trigger')) {
            inputEl.style.borderColor = '';
        } else {
            inputEl.classList.remove('border-red-500');
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // ===== CUSTOM DROPDOWN GENEL DAVRANIŞLARI =====
    
    // Initialize all custom dropdowns
    function initCustomDropdown(dropdown) {
        const trigger = dropdown.querySelector('.eval-custom-dropdown-trigger');
        const panel = dropdown.querySelector('.eval-custom-dropdown-panel');
        
        // Toggle dropdown - bu sadece bir kez eklenmeli
        if (!trigger.dataset.initialized) {
            trigger.dataset.initialized = 'true';
            trigger.addEventListener('click', function(e) {
                if (dropdown.classList.contains('disabled')) return;
                e.stopPropagation();

                // Close all other dropdowns
                document.querySelectorAll('.eval-custom-dropdown').forEach(dd => {
                    if (dd !== dropdown) {
                        dd.classList.remove('dropdown-open');
                        dd.querySelector('.eval-custom-dropdown-panel').classList.remove('open');
                    }
                });

                // Toggle this dropdown
                dropdown.classList.toggle('dropdown-open');
                panel.classList.toggle('open');

            });
        }
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.eval-custom-dropdown').forEach(dropdown => {
            dropdown.classList.remove('dropdown-open');
            dropdown.querySelector('.eval-custom-dropdown-panel').classList.remove('open');
        });
    });
    
    // Initialize all dropdowns
    document.querySelectorAll('.eval-custom-dropdown').forEach(initCustomDropdown);
    
    // State
    let selectedBrandId = '{{ request("marka_id") }}' || null;
    let selectedYear = null;
    let selectedModelId = null;
    let selectedGovdeId = null;
    let selectedYakitId = null;
    let selectedVitesId = null;
    let selectedVersiyonId = null;

    // Load brands on page load
    loadBrands();

    // If brand is pre-selected from URL, load years
    if (selectedBrandId) {
        setTimeout(() => {
            loadYears(selectedBrandId);
            document.getElementById('yil-dropdown').classList.remove('disabled');
        }, 500);
    }

    // Event listeners for custom dropdowns
    document.getElementById('marka-dropdown').addEventListener('dropdown-change', function(e) {
        selectedBrandId = e.detail.id || null;
        document.getElementById('marka-input').value = e.detail.value || '';
        document.getElementById('marka-id').value = selectedBrandId || '';
        if (e.detail.value) clearFieldError(this.querySelector('.eval-custom-dropdown-trigger'), 'marka-error');

        // Reset all subsequent fields
        resetFrom('yil');

        if (selectedBrandId) {
            loadYears(selectedBrandId);
            document.getElementById('yil-dropdown').classList.remove('disabled');
        }
    });

    document.getElementById('yil-dropdown').addEventListener('dropdown-change', function(e) {
        selectedYear = e.detail.value || null;
        document.getElementById('yil-input').value = selectedYear || '';
        if (e.detail.value) clearFieldError(this.querySelector('.eval-custom-dropdown-trigger'), 'yil-error');

        // Reset all subsequent fields
        resetFrom('model');

        if (selectedYear && selectedBrandId) {
            loadModels(selectedBrandId, selectedYear);
            document.getElementById('model-dropdown').classList.remove('disabled');
        }
    });

    document.getElementById('model-dropdown').addEventListener('dropdown-change', function(e) {
        selectedModelId = e.detail.id || null;
        document.getElementById('model-input').value = e.detail.value || '';
        document.getElementById('model-id').value = selectedModelId || '';
        if (e.detail.value) clearFieldError(this.querySelector('.eval-custom-dropdown-trigger'), 'model-error');

        // Reset all subsequent fields
        resetFrom('govde');

        if (selectedModelId) {
            loadGovdeTipleri(selectedBrandId, selectedYear, selectedModelId);
            document.getElementById('govde-dropdown').classList.remove('disabled');
        }
    });

    document.getElementById('govde-dropdown').addEventListener('dropdown-change', function(e) {
        selectedGovdeId = e.detail.id || null;
        document.getElementById('govde-input').value = e.detail.value || '';
        document.getElementById('govde-id').value = selectedGovdeId || '';

        // Reset all subsequent fields
        resetFrom('yakit');

        if (selectedGovdeId) {
            loadYakitTipleri(selectedBrandId, selectedYear, selectedModelId, selectedGovdeId);
            document.getElementById('yakit-dropdown').classList.remove('disabled');
        }
    });

    document.getElementById('yakit-dropdown').addEventListener('dropdown-change', function(e) {
        selectedYakitId = e.detail.id || null;
        document.getElementById('yakit-input').value = e.detail.value || '';
        document.getElementById('yakit-id').value = selectedYakitId || '';

        // Reset all subsequent fields
        resetFrom('vites');

        if (selectedYakitId) {
            loadVitesTipleri(selectedBrandId, selectedYear, selectedModelId, selectedGovdeId, selectedYakitId);
            document.getElementById('vites-dropdown').classList.remove('disabled');
        }
    });

    document.getElementById('vites-dropdown').addEventListener('dropdown-change', function(e) {
        selectedVitesId = e.detail.id || null;
        document.getElementById('vites-input').value = e.detail.value || '';
        document.getElementById('vites-id').value = selectedVitesId || '';

        // Reset all subsequent fields
        resetFrom('versiyon');

        if (selectedVitesId) {
            loadVersiyonlar(selectedBrandId, selectedYear, selectedModelId, selectedGovdeId, selectedYakitId, selectedVitesId);
            document.getElementById('versiyon-dropdown').classList.remove('disabled');
        }
    });

    document.getElementById('versiyon-dropdown').addEventListener('dropdown-change', function(e) {
        selectedVersiyonId = e.detail.id || null;
        document.getElementById('versiyon-input').value = e.detail.value || '';
        document.getElementById('versiyon-id').value = selectedVersiyonId || '';

        if (selectedVersiyonId) {
            loadRenkler(selectedBrandId, selectedYear, selectedModelId, selectedGovdeId, selectedYakitId, selectedVitesId, selectedVersiyonId);
            document.getElementById('kilometre-input').disabled = false;
            document.getElementById('renk-dropdown').classList.remove('disabled');
        }
    });

    document.getElementById('renk-dropdown').addEventListener('dropdown-change', function(e) {
        document.getElementById('renk-input').value = e.detail.value || '';
        document.getElementById('renk-id').value = e.detail.id || '';
        if (e.detail.value) {
            clearFieldError(this.querySelector('.eval-custom-dropdown-trigger'), 'renk-error');
        }
    });

    // Format kilometre input + clear error
    document.getElementById('kilometre-input').addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        if (this.value.trim()) clearFieldError(this, 'kilometre-error');
    });

    // Reset functions for custom dropdowns
    function resetFrom(field) {
        const fields = ['yil', 'model', 'govde', 'yakit', 'vites', 'versiyon', 'km', 'renk'];
        const index = fields.indexOf(field);

        if (index >= 0) {
            fields.slice(index).forEach(f => {
                switch(f) {
                    case 'yil':
                        resetDropdown('yil', 'Yıl Seçin');
                        selectedYear = null;
                        break;
                    case 'model':
                        resetDropdown('model', 'Model Seçin');
                        selectedModelId = null;
                        break;
                    case 'govde':
                        resetDropdown('govde', 'Gövde Tipi Seçin');
                        selectedGovdeId = null;
                        break;
                    case 'yakit':
                        resetDropdown('yakit', 'Yakıt Tipi Seçin');
                        selectedYakitId = null;
                        break;
                    case 'vites':
                        resetDropdown('vites', 'Vites Tipi Seçin');
                        selectedVitesId = null;
                        break;
                    case 'versiyon':
                        resetDropdown('versiyon', 'Versiyon Seçin');
                        selectedVersiyonId = null;
                        break;
                    case 'km':
                        document.getElementById('kilometre-input').value = '';
                        document.getElementById('kilometre-input').disabled = true;
                        break;
                    case 'renk':
                        resetDropdown('renk', 'Renk Seçin');
                        break;
                }
            });
        }
    }
    
    function resetDropdown(name, placeholder) {
        const dropdown = document.getElementById(name + '-dropdown');
        if (!dropdown) return;
        
        const trigger = dropdown.querySelector('.eval-custom-dropdown-trigger');
        const selectedText = trigger.querySelector('.selected-text');
        const panel = dropdown.querySelector('.eval-custom-dropdown-panel');
        
        selectedText.textContent = placeholder;
        selectedText.classList.add('placeholder');
        trigger.setAttribute('data-value', '');
        trigger.setAttribute('data-id', '');
        
        const inputElem = document.getElementById(name + '-input');
        if (inputElem) inputElem.value = '';
        
        const idElem = document.getElementById(name + '-id');
        if (idElem) idElem.value = '';
        
        panel.innerHTML = '';
        dropdown.classList.add('disabled');
    }

    // API functions
    async function loadBrands() {
        try {
            const response = await fetch('/api/arabam/brands');
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                const markaDropdown = document.getElementById('marka-dropdown');
                const panel = markaDropdown.querySelector('.eval-custom-dropdown-panel');
                const trigger = markaDropdown.querySelector('.eval-custom-dropdown-trigger');
                const selectedText = trigger.querySelector('.selected-text');
                panel.innerHTML = '';
                
                result.data.Items.forEach(item => {
                    const option = document.createElement('div');
                    option.className = 'eval-custom-dropdown-option';
                    option.textContent = item.Name;
                    option.setAttribute('data-value', item.Name);
                    option.setAttribute('data-id', item.Id);
                    
                    option.addEventListener('click', function(e) {
                        e.stopPropagation();
                        
                        selectedText.textContent = item.Name;
                        selectedText.classList.remove('placeholder');
                        trigger.setAttribute('data-value', item.Name);
                        trigger.setAttribute('data-id', item.Id);
                        
                        document.getElementById('marka-input').value = item.Name;
                        document.getElementById('marka-id').value = item.Id;
                        
                        panel.querySelectorAll('.eval-custom-dropdown-option').forEach(opt => opt.classList.remove('selected'));
                        option.classList.add('selected');
                        
                        markaDropdown.classList.remove('dropdown-open');
                        panel.classList.remove('open');
                        
                        // Dispatch custom event
                        const event = new CustomEvent('dropdown-change', { 
                            detail: { value: item.Name, id: item.Id } 
                        });
                        markaDropdown.dispatchEvent(event);
                    });
                    
                    panel.appendChild(option);
                    
                    // Auto-select brand if it comes from URL
                    if (selectedBrandId && item.Id == selectedBrandId) {
                        selectedText.textContent = item.Name;
                        selectedText.classList.remove('placeholder');
                        trigger.setAttribute('data-value', item.Name);
                        trigger.setAttribute('data-id', item.Id);
                        
                        document.getElementById('marka-input').value = item.Name;
                        document.getElementById('marka-id').value = item.Id;
                        
                        option.classList.add('selected');
                    }
                });
            }
        } catch (error) {
            // silently handle
        }
    }

    async function loadYears(brandId) {
        try {
            const response = await fetch(`/api/arabam/step?step=10&brandId=${brandId}`);
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                const yilDropdown = document.getElementById('yil-dropdown');
                const panel = yilDropdown.querySelector('.eval-custom-dropdown-panel');
                panel.innerHTML = '';
                
                result.data.Items.forEach(item => {
                    const option = document.createElement('div');
                    option.className = 'eval-custom-dropdown-option';
                    option.textContent = item.Name;
                    option.setAttribute('data-value', item.Name);
                    option.setAttribute('data-id', item.Id);
                    
                    option.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const trigger = yilDropdown.querySelector('.eval-custom-dropdown-trigger');
                        const selectedText = trigger.querySelector('.selected-text');
                        
                        selectedText.textContent = item.Name;
                        selectedText.classList.remove('placeholder');
                        trigger.setAttribute('data-value', item.Name);
                        
                        document.getElementById('yil-input').value = item.Name;
                        
                        panel.querySelectorAll('.eval-custom-dropdown-option').forEach(opt => opt.classList.remove('selected'));
                        option.classList.add('selected');
                        
                        yilDropdown.classList.remove('dropdown-open');
                        panel.classList.remove('open');
                        
                        // Dispatch custom event
                        const event = new CustomEvent('dropdown-change', { 
                            detail: { value: item.Name, id: item.Id } 
                        });
                        yilDropdown.dispatchEvent(event);
                    });
                    
                    panel.appendChild(option);
                });
                
                // Enable dropdown
                yilDropdown.classList.remove('disabled');

                // Auto-select if only one item
                if (result.data.Items.length === 1) {
                    const firstOption = panel.querySelector('.eval-custom-dropdown-option');
                    if (firstOption) firstOption.click();
                }
            }
        } catch (error) {
            // silently handle
        }
    }

    async function loadModels(brandId, year) {
        try {
            const response = await fetch(`/api/arabam/step?step=20&brandId=${brandId}&modelYear=${year}`);
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                const modelDropdown = document.getElementById('model-dropdown');
                const panel = modelDropdown.querySelector('.eval-custom-dropdown-panel');
                panel.innerHTML = '';
                
                result.data.Items.forEach(item => {
                    const option = document.createElement('div');
                    option.className = 'eval-custom-dropdown-option';
                    option.textContent = item.Name;
                    option.setAttribute('data-value', item.Name);
                    option.setAttribute('data-id', item.Id);
                    
                    option.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const trigger = modelDropdown.querySelector('.eval-custom-dropdown-trigger');
                        const selectedText = trigger.querySelector('.selected-text');
                        
                        selectedText.textContent = item.Name;
                        selectedText.classList.remove('placeholder');
                        
                        document.getElementById('model-input').value = item.Name;
                        document.getElementById('model-id').value = item.Id;
                        
                        panel.querySelectorAll('.eval-custom-dropdown-option').forEach(opt => opt.classList.remove('selected'));
                        option.classList.add('selected');
                        
                        modelDropdown.classList.remove('dropdown-open');
                        panel.classList.remove('open');
                        
                        // Dispatch custom event
                        const event = new CustomEvent('dropdown-change', { 
                            detail: { value: item.Name, id: item.Id } 
                        });
                        modelDropdown.dispatchEvent(event);
                    });
                    
                    panel.appendChild(option);
                });
                
                // Enable dropdown
                modelDropdown.classList.remove('disabled');

                // Auto-select if only one item
                if (result.data.Items.length === 1) {
                    const firstOption = panel.querySelector('.eval-custom-dropdown-option');
                    if (firstOption) firstOption.click();
                }
            }
        } catch (error) {
            // silently handle
        }
    }

    async function loadGovdeTipleri(brandId, year, modelIdParam) {
        try {
            const response = await fetch(`/api/arabam/step?step=30&brandId=${brandId}&modelYear=${year}&modelGroupId=${modelIdParam}`);
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                const govdeDropdown = document.getElementById('govde-dropdown');
                const panel = govdeDropdown.querySelector('.eval-custom-dropdown-panel');
                panel.innerHTML = '';
                
                result.data.Items.forEach(item => {
                    const option = document.createElement('div');
                    option.className = 'eval-custom-dropdown-option';
                    option.textContent = item.Name;
                    option.setAttribute('data-value', item.Name);
                    option.setAttribute('data-id', item.Id);
                    
                    option.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const trigger = govdeDropdown.querySelector('.eval-custom-dropdown-trigger');
                        const selectedText = trigger.querySelector('.selected-text');
                        
                        selectedText.textContent = item.Name;
                        selectedText.classList.remove('placeholder');
                        
                        document.getElementById('govde-input').value = item.Name;
                        document.getElementById('govde-id').value = item.Id;
                        
                        panel.querySelectorAll('.eval-custom-dropdown-option').forEach(opt => opt.classList.remove('selected'));
                        option.classList.add('selected');
                        
                        govdeDropdown.classList.remove('dropdown-open');
                        panel.classList.remove('open');
                        
                        // Dispatch custom event
                        const event = new CustomEvent('dropdown-change', { 
                            detail: { value: item.Name, id: item.Id } 
                        });
                        govdeDropdown.dispatchEvent(event);
                    });
                    
                    panel.appendChild(option);
                });
                
                // Enable dropdown
                govdeDropdown.classList.remove('disabled');

                // Auto-select if only one item
                if (result.data.Items.length === 1) {
                    const firstOption = panel.querySelector('.eval-custom-dropdown-option');
                    if (firstOption) firstOption.click();
                }
            }
        } catch (error) {
            // silently handle
        }
    }

    async function loadYakitTipleri(brandId, year, modelIdParam, bodyTypeId) {
        try {
            const response = await fetch(`/api/arabam/step?step=40&brandId=${brandId}&modelYear=${year}&modelGroupId=${modelIdParam}&bodyTypeId=${bodyTypeId}`);
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                const yakitDropdown = document.getElementById('yakit-dropdown');
                const panel = yakitDropdown.querySelector('.eval-custom-dropdown-panel');
                panel.innerHTML = '';
                
                result.data.Items.forEach(item => {
                    const option = document.createElement('div');
                    option.className = 'eval-custom-dropdown-option';
                    option.textContent = item.Name;
                    option.setAttribute('data-value', item.Name);
                    option.setAttribute('data-id', item.Id);
                    
                    option.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const trigger = yakitDropdown.querySelector('.eval-custom-dropdown-trigger');
                        const selectedText = trigger.querySelector('.selected-text');
                        
                        selectedText.textContent = item.Name;
                        selectedText.classList.remove('placeholder');
                        
                        document.getElementById('yakit-input').value = item.Name;
                        document.getElementById('yakit-id').value = item.Id;
                        
                        panel.querySelectorAll('.eval-custom-dropdown-option').forEach(opt => opt.classList.remove('selected'));
                        option.classList.add('selected');
                        
                        yakitDropdown.classList.remove('dropdown-open');
                        panel.classList.remove('open');
                        
                        // Dispatch custom event
                        const event = new CustomEvent('dropdown-change', { 
                            detail: { value: item.Name, id: item.Id } 
                        });
                        yakitDropdown.dispatchEvent(event);
                    });
                    
                    panel.appendChild(option);
                });
                
                // Enable dropdown
                yakitDropdown.classList.remove('disabled');

                // Auto-select if only one item
                if (result.data.Items.length === 1) {
                    const firstOption = panel.querySelector('.eval-custom-dropdown-option');
                    if (firstOption) firstOption.click();
                }
            }
        } catch (error) {
            // silently handle
        }
    }

    async function loadVitesTipleri(brandId, year, modelIdParam, bodyTypeId, fuelTypeId) {
        try {
            const response = await fetch(`/api/arabam/step?step=50&brandId=${brandId}&modelYear=${year}&modelGroupId=${modelIdParam}&bodyTypeId=${bodyTypeId}&fuelTypeId=${fuelTypeId}`);
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                const vitesDropdown = document.getElementById('vites-dropdown');
                const panel = vitesDropdown.querySelector('.eval-custom-dropdown-panel');
                panel.innerHTML = '';
                
                result.data.Items.forEach(item => {
                    const option = document.createElement('div');
                    option.className = 'eval-custom-dropdown-option';
                    option.textContent = item.Name;
                    option.setAttribute('data-value', item.Name);
                    option.setAttribute('data-id', item.Id);
                    
                    option.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const trigger = vitesDropdown.querySelector('.eval-custom-dropdown-trigger');
                        const selectedText = trigger.querySelector('.selected-text');
                        
                        selectedText.textContent = item.Name;
                        selectedText.classList.remove('placeholder');
                        
                        document.getElementById('vites-input').value = item.Name;
                        document.getElementById('vites-id').value = item.Id;
                        
                        panel.querySelectorAll('.eval-custom-dropdown-option').forEach(opt => opt.classList.remove('selected'));
                        option.classList.add('selected');
                        
                        vitesDropdown.classList.remove('dropdown-open');
                        panel.classList.remove('open');
                        
                        // Dispatch custom event
                        const event = new CustomEvent('dropdown-change', { 
                            detail: { value: item.Name, id: item.Id } 
                        });
                        vitesDropdown.dispatchEvent(event);
                    });
                    
                    panel.appendChild(option);
                });
                
                // Enable dropdown
                vitesDropdown.classList.remove('disabled');

                // Auto-select if only one item
                if (result.data.Items.length === 1) {
                    const firstOption = panel.querySelector('.eval-custom-dropdown-option');
                    if (firstOption) firstOption.click();
                }
            }
        } catch (error) {
            // silently handle
        }
    }

    async function loadVersiyonlar(brandId, year, modelIdParam, bodyTypeId, fuelTypeId, transmissionId) {
        try {
            const response = await fetch(`/api/arabam/step?step=60&brandId=${brandId}&modelYear=${year}&modelGroupId=${modelIdParam}&bodyTypeId=${bodyTypeId}&fuelTypeId=${fuelTypeId}&transmissionTypeId=${transmissionId}`);
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                const versiyonDropdown = document.getElementById('versiyon-dropdown');
                const panel = versiyonDropdown.querySelector('.eval-custom-dropdown-panel');
                panel.innerHTML = '';
                
                result.data.Items.forEach(item => {
                    const option = document.createElement('div');
                    option.className = 'eval-custom-dropdown-option';
                    option.textContent = item.Name;
                    option.setAttribute('data-value', item.Name);
                    option.setAttribute('data-id', item.Id);
                    
                    option.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const trigger = versiyonDropdown.querySelector('.eval-custom-dropdown-trigger');
                        const selectedText = trigger.querySelector('.selected-text');
                        
                        selectedText.textContent = item.Name;
                        selectedText.classList.remove('placeholder');
                        
                        document.getElementById('versiyon-input').value = item.Name;
                        document.getElementById('versiyon-id').value = item.Id;
                        
                        panel.querySelectorAll('.eval-custom-dropdown-option').forEach(opt => opt.classList.remove('selected'));
                        option.classList.add('selected');
                        
                        versiyonDropdown.classList.remove('dropdown-open');
                        panel.classList.remove('open');
                        
                        // Dispatch custom event
                        const event = new CustomEvent('dropdown-change', { 
                            detail: { value: item.Name, id: item.Id } 
                        });
                        versiyonDropdown.dispatchEvent(event);
                    });
                    
                    panel.appendChild(option);
                });
                
                // Enable dropdown
                versiyonDropdown.classList.remove('disabled');

                // Auto-select if only one item
                if (result.data.Items.length === 1) {
                    const firstOption = panel.querySelector('.eval-custom-dropdown-option');
                    if (firstOption) firstOption.click();
                }
            }
        } catch (error) {
            // silently handle
        }
    }

    async function loadRenkler(brandId, year, modelIdParam, bodyTypeId, fuelTypeId, transmissionId, versionId) {
        try {
            const response = await fetch(`/api/arabam/step?step=70&brandId=${brandId}&modelYear=${year}&modelGroupId=${modelIdParam}&bodyTypeId=${bodyTypeId}&fuelTypeId=${fuelTypeId}&transmissionTypeId=${transmissionId}&modelId=${versionId}`);
            const result = await response.json();

            if (result.success && result.data && result.data.Items) {
                const renkDropdown = document.getElementById('renk-dropdown');
                const panel = renkDropdown.querySelector('.eval-custom-dropdown-panel');
                panel.innerHTML = '';
                
                result.data.Items.forEach(item => {
                    const option = document.createElement('div');
                    option.className = 'eval-custom-dropdown-option';
                    option.textContent = item.Name;
                    option.setAttribute('data-value', item.Name);
                    option.setAttribute('data-id', item.Id);
                    
                    option.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const trigger = renkDropdown.querySelector('.eval-custom-dropdown-trigger');
                        const selectedText = trigger.querySelector('.selected-text');
                        
                        selectedText.textContent = item.Name;
                        selectedText.classList.remove('placeholder');
                        
                        document.getElementById('renk-input').value = item.Name;
                        document.getElementById('renk-id').value = item.Id;
                        
                        panel.querySelectorAll('.eval-custom-dropdown-option').forEach(opt => opt.classList.remove('selected'));
                        option.classList.add('selected');
                        
                        renkDropdown.classList.remove('dropdown-open');
                        panel.classList.remove('open');
                        
                        // Dispatch custom event
                        const event = new CustomEvent('dropdown-change', { 
                            detail: { value: item.Name, id: item.Id } 
                        });
                        renkDropdown.dispatchEvent(event);
                    });
                    
                    panel.appendChild(option);
                });
                
                // Enable dropdown
                renkDropdown.classList.remove('disabled');

                // Auto-select if only one item
                if (result.data.Items.length === 1) {
                    const firstOption = panel.querySelector('.eval-custom-dropdown-option');
                    if (firstOption) firstOption.click();
                }
            }
        } catch (error) {
            // silently handle
        }
    }

    // Tramer dropdown event listener
    const tramerDropdown = document.getElementById('tramer-dropdown');
    const tramerTutari = document.getElementById('tramer-tutari');
    
    // Add event listeners to tramer options
    tramerDropdown.querySelectorAll('.eval-custom-dropdown-option').forEach(option => {
        option.addEventListener('click', function(e) {
            e.stopPropagation();
            const value = this.getAttribute('data-value');
            const trigger = tramerDropdown.querySelector('.eval-custom-dropdown-trigger');
            const selectedText = trigger.querySelector('.selected-text');
            const panel = tramerDropdown.querySelector('.eval-custom-dropdown-panel');
            
            selectedText.textContent = this.textContent;
            selectedText.classList.remove('placeholder');
            trigger.setAttribute('data-value', value);
            
            document.getElementById('tramer-input').value = value;
            
            panel.querySelectorAll('.eval-custom-dropdown-option').forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            
            tramerDropdown.classList.remove('dropdown-open');
            panel.classList.remove('open');
            
            // Enable/disable tramer tutarı based on selection
            const tramerTutariLabel = document.getElementById('tramer-tutari-label');
            if (value === 'VAR' || value === 'AGIR_HASAR') {
                tramerTutari.disabled = false;
                tramerTutari.focus();
                if (tramerTutariLabel) tramerTutariLabel.innerHTML = 'Toplam Tramer Tutarı <span class="text-red-500">*</span>';
            } else {
                tramerTutari.disabled = true;
                tramerTutari.value = '';
                clearFieldError(tramerTutari, 'tramer-tutari-error');
                if (tramerTutariLabel) tramerTutariLabel.textContent = 'Toplam Tramer Tutarı';
            }
        });
    });

    // Part key to SVG ID mapping
    const partToSvgMap = {
        'sag_arka_camurluk': 'svg-sag_arka_camurluk',
        'arka_kaput': 'svg-arka_kaput',
        'sol_arka_camurluk': 'svg-sol_arka_camurluk',
        'sag_arka_kapi': 'svg-sag_arka_kapi',
        'sag_on_kapi': 'svg-sag_on_kapi',
        'tavan': 'svg-tavan',
        'sol_arka_kapi': 'svg-sol_arka_kapi',
        'sol_on_kapi': 'svg-sol_on_kapi',
        'sag_on_camurluk': 'svg-sag_on_camurluk',
        'motor_kaputu': 'svg-motor_kaputu',
        'sol_on_camurluk': 'svg-sol_on_camurluk',
        'on_tampon': 'svg-on_tampon',
        'arka_tampon': 'svg-arka_tampon'
    };

    // Update SVG part color based on selection
    function updateSvgPartColor(partKey) {
        const svgId = partToSvgMap[partKey];
        if (!svgId) return;

        const svgPart = document.getElementById(svgId);
        if (!svgPart) return;

        // Find which checkbox is checked for this part
        const checkedBox = document.querySelector(`.ekspertiz-checkbox[data-part="${partKey}"]:checked`);

        if (checkedBox) {
            const value = checkedBox.value;
            if (value === 'BOYALI') {
                svgPart.style.fill = '#3b82f6'; // Blue
            } else if (value === 'LOKAL_BOYALI') {
                svgPart.style.fill = '#fbbf24'; // Yellow
            } else if (value === 'DEGISMIS') {
                svgPart.style.fill = '#dc2626'; // Red
            } else {
                svgPart.style.fill = '#FFFFFF'; // Original white
            }
        } else {
            svgPart.style.fill = '#FFFFFF';
        }
    }

    // Checkbox behavior - only one can be selected per row (like radio)
    document.querySelectorAll('.ekspertiz-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const part = this.dataset.part;

            if (this.checked) {
                // Uncheck others in same row
                document.querySelectorAll(`.ekspertiz-checkbox[data-part="${part}"]`).forEach(cb => {
                    if (cb !== this) cb.checked = false;
                });
            }

            // Update SVG color
            updateSvgPartColor(part);
        });
    });

    // SVG Part names mapping
    const svgPartNames = {
        'sag_arka_camurluk': 'Sağ arka çamurluk',
        'arka_kaput': 'Arka kaput',
        'sol_arka_camurluk': 'Sol arka çamurluk',
        'sag_arka_kapi': 'Sağ arka kapı',
        'sag_on_kapi': 'Sağ ön kapı',
        'tavan': 'Tavan',
        'sol_arka_kapi': 'Sol arka kapı',
        'sol_on_kapi': 'Sol ön kapı',
        'sag_on_camurluk': 'Sağ ön çamurluk',
        'motor_kaputu': 'Motor kaputu',
        'sol_on_camurluk': 'Sol ön çamurluk',
        'on_tampon': 'Ön tampon',
        'arka_tampon': 'Arka tampon'
    };

    // Tooltip functionality
    const tooltip = document.getElementById('car-tooltip');
    const tooltipTitle = document.getElementById('tooltip-title');
    let currentTooltipPart = null;

    // Click on SVG parts to show tooltip
    document.querySelectorAll('.car-part').forEach(part => {
        part.addEventListener('click', function(e) {
            e.stopPropagation();

            const svgId = this.id;
            const partKey = svgId.replace('svg-', '');
            const partName = svgPartNames[partKey] || partKey;

            currentTooltipPart = partKey;
            tooltipTitle.textContent = partName;

            // Update selected state in tooltip
            const checkedBox = document.querySelector(`.ekspertiz-checkbox[data-part="${partKey}"]:checked`);
            const currentValue = checkedBox ? checkedBox.value : 'ORIJINAL';

            tooltip.querySelectorAll('.car-tooltip-option').forEach(opt => {
                opt.classList.toggle('selected', opt.dataset.value === currentValue);
            });

            // Position tooltip near the click
            const wrapper = document.querySelector('.ekspertiz-car-wrapper');
            const rect = wrapper.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            tooltip.style.left = Math.min(x, rect.width - 160) + 'px';
            tooltip.style.top = Math.min(y, rect.height - 120) + 'px';
            tooltip.classList.add('active');
        });
    });

    // Click on tooltip option
    tooltip.querySelectorAll('.car-tooltip-option').forEach(option => {
        option.addEventListener('click', function(e) {
            e.stopPropagation();

            const value = this.dataset.value;
            if (!currentTooltipPart) return;

            // Update checkboxes in table
            document.querySelectorAll(`.ekspertiz-checkbox[data-part="${currentTooltipPart}"]`).forEach(cb => {
                cb.checked = cb.value === value;
            });

            // Update SVG color
            updateSvgPartColor(currentTooltipPart);

            // Update tooltip selected state
            tooltip.querySelectorAll('.car-tooltip-option').forEach(opt => {
                opt.classList.toggle('selected', opt.dataset.value === value);
            });

            // Hide tooltip after selection
            setTimeout(() => {
                tooltip.classList.remove('active');
                currentTooltipPart = null;
            }, 150);
        });
    });

    // Close tooltip when clicking outside
    document.addEventListener('click', function(e) {
        if (!tooltip.contains(e.target) && !e.target.classList.contains('car-part')) {
            tooltip.classList.remove('active');
            currentTooltipPart = null;
        }
    });

    // Hover effect on table rows
    document.querySelectorAll('.damage-table tbody tr').forEach(row => {
        const checkbox = row.querySelector('.ekspertiz-checkbox');
        if (!checkbox) return;

        const partKey = checkbox.dataset.part;
        const svgId = partToSvgMap[partKey];
        if (!svgId) return;

        const svgPart = document.getElementById(svgId);
        if (!svgPart) return;

        let originalFill = '';

        row.addEventListener('mouseenter', function() {
            originalFill = svgPart.style.fill || '#FFFFFF';
            svgPart.style.fill = '#9ca3af'; // Gray on hover
        });

        row.addEventListener('mouseleave', function() {
            // Restore color based on checkbox state
            updateSvgPartColor(partKey);
        });
    });

    // Format tramer tutarı with dots + clear error
    tramerTutari.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        if (this.value.trim()) clearFieldError(this, 'tramer-tutari-error');
    });

    // Format phone number
    const telefonInput = document.getElementById('telefon-input');
    if (telefonInput) {
        telefonInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);

            // Format as 05XX XXX XX XX
            if (value.length > 0) {
                let formatted = value.slice(0, 4);
                if (value.length > 4) formatted += ' ' + value.slice(4, 7);
                if (value.length > 7) formatted += ' ' + value.slice(7, 9);
                if (value.length > 9) formatted += ' ' + value.slice(9, 11);
                this.value = formatted;
            }
        });
    }

    // Input'lara yazılınca hata mesajlarını temizle
    ['ad-input','soyad-input','telefon-input','email-input'].forEach(function(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', function() {
            const errId = id.replace('-input', '-error');
            const errEl = document.getElementById(errId);
            if (errEl) errEl.classList.add('hidden');
            el.classList.remove('border-red-500');
        });
    });

    // Form submission
    const form = document.getElementById('evaluation-form');
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const submitLoading = document.getElementById('submit-loading');

        // Validate step 3
        const adInput      = document.getElementById('ad-input');
        const soyadInput   = document.getElementById('soyad-input');
        const telefonInput = document.getElementById('telefon-input');
        const emailInput   = document.getElementById('email-input');
        // Temizle
        ['ad','soyad','telefon','email'].forEach(f => {
            const el = document.getElementById(f + '-input');
            if (el) clearFieldError(el, f + '-error');
        });

        let hasError = false;

        if (!adInput.value.trim()) {
            showFieldError(adInput, 'ad-error', 'Ad alanı zorunludur.');
            hasError = true;
        }
        if (!soyadInput.value.trim()) {
            showFieldError(soyadInput, 'soyad-error', 'Soyad alanı zorunludur.');
            hasError = true;
        }
        if (!telefonInput.value.trim() || telefonInput.value.replace(/\D/g, '').length < 10) {
            showFieldError(telefonInput, 'telefon-error', 'Geçerli bir telefon numarası girin (en az 10 rakam).');
            hasError = true;
        }
        if (!emailInput.value.trim() || !emailInput.value.includes('@')) {
            showFieldError(emailInput, 'email-error', 'Geçerli bir e-posta adresi girin.');
            hasError = true;
        }

        // Yasal onay kontrolü
        const legalCheckboxes = document.querySelectorAll('#step-3 [name^="legal_consent_"]');
        legalCheckboxes.forEach(cb => {
            const slug = cb.name.replace('legal_consent_', '');
            const errEl = document.getElementById('error-' + slug + '-evaluation');
            const isOptional = cb.dataset.isOptional === 'true';
            
            // Sadece zorunlu sözleşmeleri kontrol et
            if (!isOptional && !cb.checked) {
                hasError = true;
                if (errEl) {
                    errEl.textContent = 'Sözleşmeyi okuyup onaylamanız zorunludur.';
                    errEl.classList.remove('hidden');
                }
            } else {
                if (errEl) errEl.classList.add('hidden');
            }
        });

        if (hasError) {
            // İlk hatalı alana kaydır
            const firstError = document.querySelector('#step-3 .border-red-500, #step-3 [id$="-error"]:not(.hidden)');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        // Show loading
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');

        try {
            const formData = new FormData(form);

            // Add ekspertiz data as JSON
            const ekspertizData = {};
            document.querySelectorAll('.ekspertiz-checkbox:checked').forEach(cb => {
                ekspertizData[cb.dataset.part] = cb.value;
            });
            formData.set('ekspertiz', JSON.stringify(ekspertizData));

            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const responseText = await response.text();
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (parseError) {
                alert('Sunucu yanıtı geçersiz. Lütfen tekrar deneyin.');
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
                return;
            }

            if (result.success) {
                // Show success message
                form.innerHTML = `
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Talebiniz Alındı!</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">${result.message}</p>
                        <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                            Anasayfaya Dön
                        </a>
                    </div>
                `;
            } else {
                alert(result.message || 'Bir hata oluştu. Lütfen tekrar deneyin.');
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        } catch (error) {
            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            submitLoading.classList.add('hidden');
        }
    });
});

// Step navigation functions
let currentStep = 1;

function goToStep(step) {
    // Validate before moving forward
    if (step > currentStep) {
        if (currentStep === 1 && step >= 2) {
            const markaInput     = document.getElementById('marka-input');
            const yilInput       = document.getElementById('yil-input');
            const modelInput     = document.getElementById('model-input');
            const kilometreInput = document.getElementById('kilometre-input');
            const renkInput      = document.getElementById('renk-input');

            // Clear previous step-1 errors
            ['marka','yil','model','kilometre','renk'].forEach(function(f) {
                const trigger = document.getElementById(f + '-dropdown')?.querySelector('.eval-custom-dropdown-trigger');
                clearFieldError(f === 'kilometre' ? document.getElementById('kilometre-input') : (trigger || null), f + '-error');
            });

            let hasError = false;

            if (!markaInput.value) {
                showFieldError(document.getElementById('marka-dropdown').querySelector('.eval-custom-dropdown-trigger'), 'marka-error', 'Lütfen marka seçin.');
                hasError = true;
            }
            if (!yilInput.value) {
                showFieldError(document.getElementById('yil-dropdown').querySelector('.eval-custom-dropdown-trigger'), 'yil-error', 'Lütfen yıl seçin.');
                hasError = true;
            }
            if (!modelInput.value) {
                showFieldError(document.getElementById('model-dropdown').querySelector('.eval-custom-dropdown-trigger'), 'model-error', 'Lütfen model seçin.');
                hasError = true;
            }
            if (!kilometreInput.value.trim()) {
                showFieldError(kilometreInput, 'kilometre-error', 'Lütfen kilometre girin.');
                hasError = true;
            }
            // Renk isteğe bağlı — API veya manuel giriş yüklenmemiş olabilir, engelleme yapma

            if (hasError) {
                const firstError = document.querySelector('#step-1 [id$="-error"]:not(.hidden)');
                if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
        }

        if (currentStep === 2 && step >= 3) {
            const tramerInput  = document.getElementById('tramer-input');
            const tramerTutari = document.getElementById('tramer-tutari');

            clearFieldError(tramerTutari, 'tramer-tutari-error');

            if (tramerInput && ['VAR', 'AGIR_HASAR'].includes(tramerInput.value)) {
                if (!tramerTutari.value.trim()) {
                    showFieldError(tramerTutari, 'tramer-tutari-error', 'Hasar/tramer durumunda toplam tutar girilmesi zorunludur.');
                    tramerTutari.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    tramerTutari.focus();
                    return;
                }
            }
        }
    }

    // Hide all steps
    document.querySelectorAll('.wizard-step').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.step-dot').forEach(d => d.classList.remove('active'));

    // Show target step
    document.getElementById('step-' + step).classList.add('active');
    document.getElementById('dot-' + step).classList.add('active');

    currentStep = step;

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function goToStep2() {
    goToStep(2);
}

function goToStep1() {
    goToStep(1);
}

function resetAllToOriginal() {
    // Uncheck all
    document.querySelectorAll('.ekspertiz-checkbox').forEach(cb => {
        cb.checked = false;
    });
    // Check all ORIJINAL
    document.querySelectorAll('.ekspertiz-checkbox[value="ORIJINAL"]').forEach(cb => {
        cb.checked = true;
    });
    // Reset all SVG parts to white
    document.querySelectorAll('.car-part').forEach(part => {
        part.style.fill = '#FFFFFF';
    });
}
</script>
@endpush
@endsection
