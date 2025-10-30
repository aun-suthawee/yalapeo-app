@extends('sandbox::layouts.master')

@section('stylesheet-content')
    <link rel="stylesheet" href="{{ asset('assets/common/css/sandbox-breadcrumb.css') }}">
    <style>
        :root {
            --primary-orange: #ff6b35;
            --primary-green: #2ecc71;
            --primary-blue: #3498db;
            --primary-purple: #9b59b6;
            --accent-white: #ffffff;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
            --dark-gray: #6c757d;
            --text-dark: #2c3e50;
            --success-green: #28a745;
            --warning-orange: #ffc107;
            --danger-red: #dc3545;
            --shadow-soft: 0 4px 12px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 8px 25px rgba(0, 0, 0, 0.15);
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        .experiment-builder {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-purple) 100%);
            min-height: 100vh;
            padding: 40px 0;
        }

        .builder-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .sidebar {
            background: var(--accent-white);
            border-radius: var(--border-radius);
            padding: 24px;
            height: fit-content;
            position: sticky;
            top: 24px;
            transition: var(--transition);
        }

        .sidebar.drag-over-delete {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 3px dashed var(--danger-red);
            box-shadow: 0 8px 24px rgba(220, 53, 69, 0.3);
        }

        .sidebar.drag-over-delete::after {
            content: '🗑️ วางที่นี่เพื่อลบ';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
            font-weight: bold;
            color: var(--danger-red);
            pointer-events: none;
            z-index: 10;
            background: var(--accent-white);
            padding: 20px 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-soft);
        }

        .sidebar.drag-over-delete .sidebar-title,
        .sidebar.drag-over-delete p,
        .sidebar.drag-over-delete .parameter-item {
            opacity: 0.3;
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .parameter-item {
            background: var(--light-gray);
            border: 2px dashed var(--medium-gray);
            border-radius: var(--border-radius);
            padding: 16px;
            margin-bottom: 12px;
            cursor: grab;
            transition: var(--transition);
        }

        .parameter-item:hover {
            background: var(--medium-gray);
            border-color: var(--primary-orange);
            transform: translateX(4px);
        }

        .parameter-item:active {
            cursor: grabbing;
        }

        .parameter-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-orange), var(--warning-orange));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-white);
            font-size: 18px;
            margin-bottom: 8px;
        }

        .parameter-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 14px;
            margin-bottom: 4px;
        }

        .parameter-desc {
            font-size: 12px;
            color: var(--dark-gray);
        }

        .impact-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 6px;
        }

        .impact-high {
            background: #fee2e2;
            color: var(--danger-red);
        }

        .impact-medium {
            background: #fff3cd;
            color: #856404;
        }

        .impact-low {
            background: #d1ecf1;
            color: #0c5460;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .info-panel {
            background: var(--accent-white);
            border-radius: var(--border-radius);
            padding: 24px;
            box-shadow: var(--shadow-soft);
        }

        .info-panel h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .info-panel p {
            color: var(--dark-gray);
            margin-bottom: 16px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
        }

        .info-item {
            text-align: center;
            padding: 12px;
            background: var(--light-gray);
            border-radius: 8px;
            transition: var(--transition);
        }

        .info-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-soft);
        }

        .info-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-orange);
        }

        .info-label {
            font-size: 13px;
            color: var(--dark-gray);
            color: #718096;
            margin-top: 4px;
        }

        .scenarios-container {
            background: white;
            border-radius: 16px;
            padding: 24px;
        }

        .scenarios-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .scenarios-title {
            font-size: 20px;
            font-weight: 700;
            color: #2d3748;
        }

        .scenario-card {
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            transition: all 0.2s;
            position: relative;
        }

        .scenario-card:hover {
            border-color: var(--primary-orange);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .scenario-card.drag-over {
            border-color: var(--primary-orange);
            border-width: 3px;
            background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
        }

        .scenario-card.baseline {
            background: linear-gradient(135deg, var(--primary-orange)15, var(--primary-purple)15);
            border-color: var(--primary-orange);
        }

        .scenario-header {
            display: flex;
            justify-content: between;
            align-items: start;
            margin-bottom: 16px;
            gap: 16px;
        }

        .scenario-info {
            flex: 1;
        }

        .scenario-number {
            width: 32px;
            height: 32px;
            background: var(--primary-orange);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .scenario-name {
            font-size: 16px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 4px;
        }

        .scenario-desc {
            font-size: 13px;
            color: #718096;
        }

        .scenario-actions {
            display: flex;
            gap: 8px;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-run {
            background: var(--success-green);
            color: white;
        }

        .btn-run:hover {
            background: #059669;
        }

        .btn-edit {
            background: var(--warning-orange);
            color: white;
        }

        .btn-edit:hover {
            background: #d97706;
        }

        .btn-delete {
            background: var(--danger-red);
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
        }

        .parameters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 16px;
        }

        .param-display {
            background: white;
            border-radius: 8px;
            padding: 12px;
            border-left: 4px solid var(--primary-orange);
            position: relative;
            transition: all 0.2s;
        }

        .param-display[draggable="true"] {
            cursor: move;
            padding-left: 32px;
        }

        .param-display[draggable="true"]:hover {
            background: #f8f9ff;
            transform: translateX(2px);
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
        }

        .param-display .drag-handle {
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: #cbd5e0;
            font-size: 14px;
            letter-spacing: -2px;
            cursor: move;
        }

        .param-display[draggable="true"]:hover .drag-handle {
            color: var(--primary-orange);
        }

        .param-label {
            font-size: 12px;
            color: #718096;
            margin-bottom: 4px;
        }

        .param-value {
            font-size: 18px;
            font-weight: 700;
            color: #2d3748;
        }

        .param-unit {
            font-size: 13px;
            color: var(--primary-orange);
            margin-left: 4px;
        }

        /* Scenario Drop Zone */
        .scenario-parameters-section {
            position: relative;
        }

        .scenario-drop-zone {
            border: 2px dashed #cbd5e0;
            border-radius: 10px;
            padding: 24px;
            text-align: center;
            color: #a0aec0;
            min-height: 100px;
            margin-top: 12px;
            transition: all 0.3s;
            background: #fafafa;
        }

        .scenario-drop-zone:hover {
            border-color: var(--primary-orange);
            background: #f8f9ff;
        }

        .scenario-drop-zone.drag-over {
            border-color: var(--primary-orange);
            border-width: 3px;
            background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
            transform: scale(1.02);
        }

        .scenario-drop-zone .drop-zone-content {
            pointer-events: none;
        }

        .scenario-drop-zone i {
            font-size: 28px;
            display: block;
            color: #cbd5e0;
        }

        .scenario-drop-zone.drag-over i {
            color: var(--primary-orange);
            animation: bounce 0.6s infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        .scenario-drop-zone p {
            font-size: 13px;
            color: #94a3b8;
        }

        .drop-zone {
            border: 3px dashed #cbd5e0;
            border-radius: 12px;
            padding: 32px;
            text-align: center;
            color: #a0aec0;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .drop-zone.drag-over {
            border-color: var(--primary-orange);
            background: #f0f4ff;
        }

        .drop-zone i {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .btn-add-scenario {
            background: var(--primary-orange);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add-scenario:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .btn-run-all {
            background: var(--success-green);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-run-all:hover {
            background: #059669;
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }

        .status-pending {
            background: #fbbf24;
        }

        .status-running {
            background: var(--primary-blue);
            animation: pulse 1.5s infinite;
        }

        .status-completed {
            background: var(--success-green);
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .results-preview {
            background: #f0fdf4;
            border: 2px solid var(--success-green);
            border-radius: 8px;
            padding: 12px;
            margin-top: 12px;
            font-size: 13px;
        }

        .results-preview strong {
            color: #065f46;
        }

        /* Modal Styles */
        .modal-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-purple));
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .modal-content {
            border-radius: 12px;
            border: none;
        }

        .range-slider {
            width: 100%;
        }

        .range-value {
            display: inline-block;
            background: var(--primary-orange);
            color: white;
            padding: 4px 12px;
            border-radius: 8px;
            font-weight: 600;
            margin-left: 12px;
        }

        /* Edit Modal Specific Styles */
        .modal-header.bg-warning {
            background: linear-gradient(135deg, var(--warning-orange), #d97706) !important;
        }

        .available-params-container {
            max-height: 500px;
            overflow-y: auto;
            padding: 8px;
        }

        .param-drag-item {
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 10px;
            cursor: grab;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .param-drag-item:hover {
            background: #edf2f7;
            border-color: var(--primary-orange);
            transform: translateX(4px);
        }

        .param-drag-item:active {
            cursor: grabbing;
        }

        .param-drag-item .param-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-purple));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            flex-shrink: 0;
        }

        .param-drag-item .param-info {
            flex: 1;
        }

        .param-drag-item .param-name {
            font-weight: 600;
            color: #2d3748;
            font-size: 13px;
        }

        .param-drag-item .param-desc {
            font-size: 11px;
            color: #718096;
        }

        .edit-drop-zone {
            border: 3px dashed #cbd5e0;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            color: #a0aec0;
            min-height: 150px;
            transition: all 0.2s;
            margin-bottom: 20px;
        }

        .edit-drop-zone.drag-over {
            border-color: var(--warning-orange);
            background: #fffbeb;
        }

        .edit-drop-zone .empty-state {
            pointer-events: none;
        }

        .edit-drop-zone .empty-state i {
            font-size: 40px;
            color: #cbd5e0;
        }

        .edit-drop-zone.has-items .empty-state {
            display: none;
        }

        .selected-params-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .selected-param-item {
            background: white;
            border: 2px solid var(--primary-orange);
            border-radius: 10px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .selected-param-item .param-label-section {
            flex: 0 0 180px;
        }

        .selected-param-item .param-label-section strong {
            display: block;
            color: #2d3748;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .selected-param-item .param-label-section small {
            color: #718096;
            font-size: 12px;
        }

        .selected-param-item .param-input-section {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .selected-param-item .param-input-section input {
            flex: 1;
        }

        .selected-param-item .param-value-display {
            min-width: 80px;
            text-align: center;
            background: var(--primary-orange);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
        }

        .selected-param-item .btn-remove-param {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: none;
            background: var(--danger-red);
            color: white;
            cursor: pointer;
            transition: all 0.2s;
        }

        .selected-param-item .btn-remove-param:hover {
            background: #dc2626;
        }

        /* Baseline Info Styles */
        .baseline-info-box {
            background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
            border: 2px solid var(--primary-blue);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 0;
        }

        .baseline-info-box .baseline-title {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1e40af;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .baseline-info-box .baseline-title i {
            font-size: 20px;
        }

        .baseline-info-box .baseline-desc {
            color: #1e3a8a;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .baseline-filter-info {
            background: white;
            border-radius: 8px;
            padding: 12px 16px;
            border-left: 4px solid var(--primary-blue);
        }

        .baseline-filter-info .filter-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .baseline-filter-info .filter-value {
            font-size: 14px;
            color: #1e293b;
            font-weight: 600;
        }

        /* Drag Outside to Delete Zone */
        .delete-zone {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 80px;
            height: 80px;
            background: var(--danger-red);
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
            z-index: 9999;
            transition: all 0.3s;
        }

        .delete-zone.active {
            display: flex;
            animation: pulse-delete 1.5s infinite;
        }

        .delete-zone.drag-over {
            transform: scale(1.2);
            background: #dc2626;
        }

        @keyframes pulse-delete {
            0%, 100% {
                box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
            }
            50% {
                box-shadow: 0 8px 30px rgba(239, 68, 68, 0.8);
            }
        }

        /* Editable Value Input */
        .param-value-editable {
            background: white;
            border: 2px solid var(--primary-orange);
            border-radius: 8px;
            padding: 8px 16px;
            min-width: 100px;
            max-width: 120px;
            text-align: center;
            font-weight: 700;
            font-size: 16px;
            color: #2d3748;
            cursor: text;
            transition: all 0.2s;
        }

        .param-value-editable:hover {
            border-color: #5568d3;
            background: #f8f9ff;
        }

        .param-value-editable:focus {
            outline: none;
            border-color: #5568d3;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
            background: white;
        }

        /* Remove number input arrows */
        .param-value-editable::-webkit-outer-spin-button,
        .param-value-editable::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .param-value-editable[type=number] {
            -moz-appearance: textfield;
        }

        /* Editable Value Input for inline editing in main page */
        .param-value-editable-inline {
            background: transparent;
            border: 2px solid transparent;
            border-radius: 6px;
            padding: 4px 8px;
            width: 80px;
            text-align: center;
            font-weight: 700;
            font-size: 18px;
            color: #2d3748;
            cursor: text;
            transition: all 0.2s;
        }

        .param-value-editable-inline:hover {
            border-color: var(--primary-orange);
            background: rgba(102, 126, 234, 0.05);
        }

        .param-value-editable-inline:focus {
            outline: none;
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            background: white;
        }

        /* Remove number input arrows */
        .param-value-editable-inline::-webkit-outer-spin-button,
        .param-value-editable-inline::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .param-value-editable-inline[type=number] {
            -moz-appearance: textfield;
        }

        /* Draggable Parameter Items in Modal */
        .selected-param-item[draggable="true"] {
            cursor: move;
        }

        .selected-param-item[draggable="true"]:hover {
            background: #f8f9ff;
            border-color: var(--primary-orange);
        }

        .selected-param-item[draggable="true"]::before {
            content: '⋮⋮';
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: #cbd5e0;
            font-size: 16px;
            letter-spacing: -2px;
        }

        .selected-param-item {
            position: relative;
            padding-left: 30px;
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .builder-layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: relative;
                top: 0;
            }

            .modal-dialog.modal-xl {
                max-width: 95%;
            }
        }

        @media (max-width: 768px) {
            .experiment-builder {
                padding: 20px 0;
            }

            .d-flex.gap-2 {
                flex-direction: column;
                width: 100%;
            }

            .d-flex.gap-2 .btn {
                width: 100%;
            }

            .scenario-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .scenario-actions {
                width: 100%;
                justify-content: flex-start;
                margin-top: 10px;
            }

            .parameters-grid {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .selected-param-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .selected-param-item .param-label-section {
                flex: unset;
                width: 100%;
            }

            .selected-param-item .param-input-section {
                width: 100%;
            }

            .modal-body .row {
                flex-direction: column;
            }

            .modal-body .row .col-md-4,
            .modal-body .row .col-md-8,
            .modal-body .row .col-md-6 {
                width: 100%;
                max-width: 100%;
            }

            .available-params-container {
                max-height: 300px;
            }

            .delete-zone {
                bottom: 10px;
                right: 10px;
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .experiment-builder {
                padding: 10px 0;
            }

            h2 {
                font-size: 1.3rem;
            }

            .scenario-card {
                padding: 15px;
            }

            .btn-icon {
                width: 32px;
                height: 32px;
            }

            .baseline-info-box {
                padding: 15px;
            }

            .param-drag-item {
                padding: 10px;
            }
        }

        /* Loading Indicator */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 99999;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-spinner {
            background: white;
            border-radius: 16px;
            padding: 32px 48px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .loading-spinner .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #e2e8f0;
            border-top-color: var(--primary-orange);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 16px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-spinner p {
            margin: 0;
            color: #2d3748;
            font-weight: 600;
            font-size: 16px;
        }

        /* Parameter duplicate warning */
        .param-display.duplicate-warning {
            animation: shake 0.5s;
            border-left-color: var(--danger-red) !important;
            background: #fee2e2 !important;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .scenario-card.duplicate-error {
            border-color: var(--danger-red) !important;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%) !important;
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.3) !important;
            cursor: not-allowed !important;
        }

        .scenario-card.duplicate-error::before {
            content: '⚠️ มีพารามิเตอร์นี้อยู่แล้ว';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 18px;
            font-weight: bold;
            color: #dc2626;
            pointer-events: none;
            z-index: 10;
            background: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            opacity: 0.95;
        }
    </style>
@endsection

@section('content')
    <div class="experiment-builder">
        <div class="container-fluid" style="max-width: 1400px;">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="text-white">
                    <h2 class="mb-1">
                        <i class="fas fa-flask me-2"></i>{{ $experiment->name }}
                    </h2>
                    <p class="mb-0 text-white-50">
                        <span class="status-indicator status-{{ $experiment->status }}"></span>
                        {{ ucfirst($experiment->status) }} | ปีการศึกษา พ.ศ. {{ $experiment->base_year + 543 }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success btn-run-all" onclick="runAllScenarios()">
                        <i class="fas fa-play"></i> Run All Scenarios
                    </button>
                    <a href="{{ route('sandbox.experiments.show', $experiment) }}" class="btn btn-light">
                        <i class="fas fa-eye me-2"></i>ดูผลลัพธ์
                    </a>
                    <a href="{{ route('sandbox.experiments.index') }}" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>

            <!-- Main Layout -->
            <div class="builder-layout">
                <!-- Sidebar: Parameters -->
                <div class="sidebar">
                    <h3 class="sidebar-title">
                        <i class="fas fa-sliders-h"></i>
                        Parameters
                    </h3>
                    <p style="font-size: 13px; color: #718096; margin-bottom: 16px;">
                        ลากพารามิเตอร์ไปยัง Scenario
                    </p>

                    @foreach ($availableParameters as $key => $param)
                        <div class="parameter-item" draggable="true" data-param="{{ $key }}">
                            <div class="parameter-icon">
                                <i class="fas fa-{{ $param['type'] === 'number' ? 'hashtag' : 'percent' }}"></i>
                            </div>
                            <div class="parameter-name">{{ $param['label'] }}</div>
                            <div class="parameter-desc">{{ $param['min'] }}-{{ $param['max'] }} {{ $param['unit'] }}</div>
                            <span class="impact-badge impact-{{ $param['impact'] }}">
                                Impact: {{ ucfirst($param['impact']) }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- Main Content -->
                <div class="main-content">
                    <!-- Info Panel -->
                    <div class="info-panel">
                        <h3>{{ $experiment->name }}</h3>
                        <p>{{ $experiment->description }}</p>

                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-value">{{ $experiment->scenarios->count() }}</div>
                                <div class="info-label">Scenarios</div>
                            </div>
                            <div class="info-item">
                                <div class="info-value">{{ $experiment->results->count() }}</div>
                                <div class="info-label">Results</div>
                            </div>
                            <div class="info-item">
                                <div class="info-value">{{ $experiment->base_year + 543 }}</div>
                                <div class="info-label">ปีการศึกษา</div>
                            </div>
                            <div class="info-item">
                                <div class="info-value">
                                    <i class="fas fa-{{ $experiment->is_public ? 'globe' : 'lock' }}"></i>
                                </div>
                                <div class="info-label">{{ $experiment->is_public ? 'Public' : 'Private' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Scenarios -->
                    <div class="scenarios-container">
                        <div class="scenarios-header">
                            <h3 class="scenarios-title">
                                <i class="fas fa-layer-group me-2"></i>Scenarios
                            </h3>
                            <button type="button" class="btn-add-scenario" onclick="openAddScenarioModal()">
                                <i class="fas fa-plus"></i>
                                Add Scenario
                            </button>
                        </div>

                        <div id="scenarios-list">
                            @foreach ($experiment->scenarios as $scenario)
                                <div class="scenario-card {{ $scenario->order === 1 ? 'baseline' : '' }}"
                                    data-scenario-id="{{ $scenario->id }}">
                                    
                                    @if ($scenario->order === 1)
                                        {{-- Baseline: แสดงเฉพาะ baseline-info-box ไม่แสดง scenario-header เพื่อไม่ให้ซ้ำ --}}
                                        <div class="baseline-info-box">
                                            <div class="scenario-header" style="margin-bottom: 0;">
                                                <div class="scenario-number">{{ $scenario->order }}</div>
                                                <div class="scenario-info" style="flex: 1;">
                                                    <div class="baseline-title" style="margin-bottom: 8px;">
                                                        <i class="fas fa-database"></i>
                                                        <span>Baseline</span>
                                                    </div>
                                                    <div class="baseline-desc">
                                                        ข้อมูลดิบ (ไม่มีการปรับเปลี่ยนพารามิเตอร์) แสดงข้อมูลตามสภาพจริง
                                                    </div>
                                                </div>
                                                <div class="scenario-actions">
                                                    <button class="btn-icon btn-run" onclick="runScenario({{ $scenario->id }})"
                                                        title="Run Calculation">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @if ($experiment->baseline_filters)
                                                <div class="baseline-filter-info" aria-label="baseline-filters" style="margin-top: 16px;">
                                                    <div class="filter-label">
                                                        <i class="fas fa-filter me-1"></i>กรองข้อมูล
                                                    </div>
                                                    <div class="filter-value">
                                                        @php
                                                            $filters = is_array($experiment->baseline_filters)
                                                                ? $experiment->baseline_filters
                                                                : json_decode($experiment->baseline_filters, true);

                                                            $filterLabels = [
                                                                'school_id' => 'โรงเรียน',
                                                                'department' => 'สังกัด',
                                                                'district' => 'อำเภอ',
                                                                'sub_district' => 'ตำบล',
                                                            ];

                                                            $parts = [];
                                                            foreach ($filters as $key => $value) {
                                                                $label = $filterLabels[$key] ?? $key;
                                                                $parts[] = "{$label}: {$value}";
                                                            }
                                                            echo implode(' | ', $parts);
                                                        @endphp
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        {{-- Scenario อื่นๆ: แสดง scenario-header และ parameters --}}
                                        <div class="scenario-header">
                                            <div class="scenario-number">{{ $scenario->order }}</div>
                                            <div class="scenario-info">
                                                <div class="scenario-name">{{ $scenario->name }}</div>
                                                <div class="scenario-desc">{{ $scenario->description }}</div>
                                            </div>
                                            <div class="scenario-actions">
                                                <button class="btn-icon btn-run" onclick="runScenario({{ $scenario->id }})"
                                                    title="Run Calculation">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <button class="btn-icon btn-edit"
                                                    onclick="editScenario({{ $scenario->id }}, '{{ addslashes($scenario->name) }}', '{{ addslashes($scenario->description) }}')"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn-icon btn-delete"
                                                    onclick="deleteScenario({{ $scenario->id }})" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="scenario-parameters-section">
                                            @if (count($scenario->parameters) > 0)
                                                <div class="parameters-grid" id="params-grid-{{ $scenario->id }}">
                                                    @foreach ($scenario->parameters as $key => $value)
                                                        @if (isset($availableParameters[$key]))
                                                            <div class="param-display" draggable="true" 
                                                                 data-scenario-id="{{ $scenario->id }}"
                                                                 data-param-key="{{ $key }}">
                                                                <div class="drag-handle">⋮⋮</div>
                                                                <div class="param-label">
                                                                    {{ $availableParameters[$key]['label'] }}
                                                                </div>
                                                                <div class="param-value">
                                                                    <input type="number" 
                                                                           class="param-value-editable-inline" 
                                                                           value="{{ $value }}"
                                                                           data-scenario-id="{{ $scenario->id }}"
                                                                           data-param-key="{{ $key }}"
                                                                           min="{{ $availableParameters[$key]['min'] }}"
                                                                           max="{{ $availableParameters[$key]['max'] }}">
                                                                    <span class="param-unit">{{ $availableParameters[$key]['unit'] }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                            
                                            <!-- Drop Zone -->
                                            <div class="scenario-drop-zone" 
                                                 data-scenario-id="{{ $scenario->id }}"
                                                 id="drop-zone-{{ $scenario->id }}">
                                                <div class="drop-zone-content">
                                                    <i class="fas fa-hand-pointer mb-2"></i>
                                                    <p class="mb-0">ลากพารามิเตอร์มาที่นี่</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($scenario->results && $scenario->results->isNotEmpty())
                                        <div class="results-preview">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>คำนวณเสร็จแล้ว!</strong>
                                            คลิกปุ่ม "ดูผลลัพธ์" เพื่อดู charts เปรียบเทียบ
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>กำลังบันทึกข้อมูล...</p>
        </div>
    </div>

    <!-- Add Scenario Modal -->
    <div class="modal fade" id="addScenarioModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>เพิ่ม Scenario ใหม่
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addScenarioForm">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">ชื่อ Scenario</label>
                                <input type="text" class="form-control" id="scenarioName" required
                                    placeholder="เช่น: เพิ่มครู 20%">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">คำอธิบาย</label>
                                <textarea class="form-control" id="scenarioDescription" rows="2" placeholder="อธิบายสถานการณ์นี้..."></textarea>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <!-- Left: Available Parameters -->
                            <div class="col-md-4">
                                <h6 class="mb-3">
                                    <i class="fas fa-cubes me-2"></i>พารามิเตอร์ที่ใช้ได้
                                </h6>
                                <div id="addAvailableParams" class="available-params-container">
                                    @foreach ($availableParameters as $key => $param)
                                        <div class="param-drag-item" draggable="true"
                                            data-param-key="{{ $key }}">
                                            <div class="param-icon">
                                                <i class="{{ $param['icon'] ?? 'fas fa-sliders-h' }}"></i>
                                            </div>
                                            <div class="param-info">
                                                <div class="param-name">{{ $param['label'] }}</div>
                                                <div class="param-desc">{{ $param['unit'] }}</div>
                                            </div>
                                            <div class="impact-badge impact-{{ $param['impact'] }}">
                                                {{ ucfirst($param['impact']) }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Right: Drop Zone & Current Parameters -->
                            <div class="col-md-8">
                                <h6 class="mb-3">
                                    <i class="fas fa-hand-pointer me-2"></i>พารามิเตอร์ที่เลือก
                                    <small class="text-muted">(ลากมาที่นี่และแก้ไขค่าได้)</small>
                                </h6>

                                <div id="addDropZone" class="edit-drop-zone">
                                    <div class="empty-state">
                                        <i class="fas fa-hand-pointer d-block mb-2"></i>
                                        <p class="mb-0">ลากพารามิเตอร์มาที่นี่</p>
                                    </div>
                                </div>

                                <div id="addSelectedParams" class="selected-params-container">
                                    <!-- Dynamic parameters will be added here -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary" onclick="submitScenario()">
                        <i class="fas fa-check me-2"></i>เพิ่ม Scenario
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Scenario Modal -->
    <div class="modal fade" id="editScenarioModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>แก้ไข Scenario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editScenarioForm">
                        <input type="hidden" id="editScenarioId">

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Scenario Name</label>
                                <input type="text" class="form-control" id="editScenarioName" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" id="editScenarioDescription" rows="2"></textarea>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <!-- Left: Available Parameters -->
                            <div class="col-md-4">
                                <h6 class="mb-3">
                                    <i class="fas fa-cubes me-2"></i>พารามิเตอร์ที่ใช้ได้
                                </h6>
                                <div id="editAvailableParams" class="available-params-container">
                                    @foreach ($availableParameters as $key => $param)
                                        <div class="param-drag-item" draggable="true"
                                            data-param-key="{{ $key }}">
                                            <div class="param-icon">
                                                <i class="{{ $param['icon'] ?? 'fas fa-sliders-h' }}"></i>
                                            </div>
                                            <div class="param-info">
                                                <div class="param-name">{{ $param['label'] }}</div>
                                                <div class="param-desc">{{ $param['unit'] }}</div>
                                            </div>
                                            <div class="impact-badge impact-{{ $param['impact'] }}">
                                                {{ ucfirst($param['impact']) }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Right: Drop Zone & Current Parameters -->
                            <div class="col-md-8">
                                <h6 class="mb-3">
                                    <i class="fas fa-hand-pointer me-2"></i>พารามิเตอร์ที่เลือก
                                    <small class="text-muted">(ลากมาที่นี่และแก้ไขค่าได้)</small>
                                </h6>

                                <div id="editDropZone" class="edit-drop-zone">
                                    <div class="empty-state">
                                        <i class="fas fa-hand-pointer d-block mb-2"></i>
                                        <p class="mb-0">ลากพารามิเตอร์มาที่นี่</p>
                                    </div>
                                </div>

                                <div id="editSelectedParams" class="selected-params-container">
                                    <!-- Dynamic parameters will be added here -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-warning text-white" onclick="submitEditScenario()">
                        <i class="fas fa-save me-2"></i>บันทึกการแก้ไข
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script-content')
    <script>
        const experimentId = {{ $experiment->id }};
        const availableParameters = @json($availableParameters);

        // Add Scenario Modal
        let currentAddParameters = {};

        function openAddScenarioModal() {
            // Reset parameters
            currentAddParameters = {};
            renderAddParameters();

            const modal = new bootstrap.Modal(document.getElementById('addScenarioModal'));
            modal.show();
        }

        function renderAddParameters() {
            const container = document.getElementById('addSelectedParams');
            const dropZone = document.getElementById('addDropZone');

            container.innerHTML = '';

            if (Object.keys(currentAddParameters).length === 0) {
                dropZone.classList.remove('has-items');
                return;
            }

            dropZone.classList.add('has-items');

            Object.entries(currentAddParameters).forEach(([key, value]) => {
                const param = availableParameters[key];
                if (!param) return;

                const item = document.createElement('div');
                item.className = 'selected-param-item';
                item.draggable = true;
                item.dataset.paramKey = key;
                item.innerHTML = `
            <div class="param-label-section">
                <strong>${param.label}</strong>
                <small>${param.unit}</small>
            </div>
            <div class="param-input-section">
                <input type="range" 
                       class="form-range" 
                       id="add-param-range-${key}"
                       min="${param.min}" 
                       max="${param.max}" 
                       value="${value}"
                       step="1"
                       oninput="updateAddParamValue('${key}', this.value)">
                <input type="number" 
                       class="param-value-editable" 
                       id="add-value-${key}"
                       min="${param.min}"
                       max="${param.max}"
                       value="${value}"
                       onchange="updateAddParamValueDirect('${key}', this.value)">
            </div>
            <button type="button" class="btn-remove-param" onclick="removeAddParam('${key}')" title="ลบพารามิเตอร์">
                <i class="fas fa-times"></i>
            </button>
        `;
                container.appendChild(item);
                
                // Add drag listeners
                item.addEventListener('dragstart', handleParamDragStart);
                item.addEventListener('dragend', handleParamDragEnd);
            });
        }

        function updateAddParamValue(key, value) {
            const param = availableParameters[key];
            value = Math.max(param.min, Math.min(param.max, parseFloat(value)));
            currentAddParameters[key] = value;
            document.getElementById(`add-value-${key}`).value = value;
        }

        function updateAddParamValueDirect(key, value) {
            const param = availableParameters[key];
            value = Math.max(param.min, Math.min(param.max, parseFloat(value) || param.min));
            currentAddParameters[key] = value;
            document.getElementById(`add-param-range-${key}`).value = value;
            document.getElementById(`add-value-${key}`).value = value;
        }

        function removeAddParam(key) {
            delete currentAddParameters[key];
            renderAddParameters();
        }

        function submitScenario() {
            const name = document.getElementById('scenarioName').value;
            const description = document.getElementById('scenarioDescription').value;

            if (!name) {
                alert('กรุณาระบุชื่อ Scenario');
                return;
            }

            fetch(`/sandbox/experiments/${experimentId}/scenarios`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name,
                        description,
                        parameters: currentAddParameters
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาด');
                });
        }

        // Run Single Scenario
        function runScenario(scenarioId) {
            if (!confirm('คำนวณ scenario นี้?')) return;

            const btn = event.target.closest('button');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/sandbox/experiments/${experimentId}/scenarios/${scenarioId}/run`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.error);
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-play"></i>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาด');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-play"></i>';
                });
        }

        // Run All Scenarios
        function runAllScenarios() {
            if (!confirm('คำนวณทุก scenarios?')) return;

            const btn = event.target;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Computing...';

            fetch(`/sandbox/experiments/${experimentId}/run-all`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.error);
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-play"></i> Run All Scenarios';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาด');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-play"></i> Run All Scenarios';
                });
        }

        // Edit Scenario
        let currentEditScenarioId = null;
        let currentEditParameters = {};

        function editScenario(scenarioId, currentName, currentDesc) {
            // Store current scenario ID
            currentEditScenarioId = scenarioId;

            // Populate modal fields
            document.getElementById('editScenarioId').value = scenarioId;
            document.getElementById('editScenarioName').value = currentName;
            document.getElementById('editScenarioDescription').value = currentDesc;

            // Fetch current parameters from server
            fetch(`/sandbox/experiments/${experimentId}/scenarios/${scenarioId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentEditParameters = data.scenario.parameters || {};
                        renderEditParameters();

                        // Open modal
                        const modal = new bootstrap.Modal(document.getElementById('editScenarioModal'));
                        modal.show();
                    } else {
                        alert('Error loading scenario: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการโหลดข้อมูล');
                });
        }

        function renderEditParameters() {
            const container = document.getElementById('editSelectedParams');
            const dropZone = document.getElementById('editDropZone');

            container.innerHTML = '';

            if (Object.keys(currentEditParameters).length === 0) {
                dropZone.classList.remove('has-items');
                return;
            }

            dropZone.classList.add('has-items');

            Object.entries(currentEditParameters).forEach(([key, value]) => {
                const param = availableParameters[key];
                if (!param) return;

                const item = document.createElement('div');
                item.className = 'selected-param-item';
                item.draggable = true;
                item.dataset.paramKey = key;
                item.innerHTML = `
            <div class="param-label-section">
                <strong>${param.label}</strong>
                <small>${param.unit}</small>
            </div>
            <div class="param-input-section">
                <input type="range" 
                       class="form-range" 
                       id="edit-param-range-${key}"
                       min="${param.min}" 
                       max="${param.max}" 
                       value="${value}"
                       step="1"
                       oninput="updateEditParamValue('${key}', this.value)">
                <input type="number" 
                       class="param-value-editable" 
                       id="edit-value-${key}"
                       min="${param.min}"
                       max="${param.max}"
                       value="${value}"
                       onchange="updateEditParamValueDirect('${key}', this.value)">
            </div>
            <button type="button" class="btn-remove-param" onclick="removeEditParam('${key}')" title="ลบพารามิเตอร์">
                <i class="fas fa-times"></i>
            </button>
        `;
                container.appendChild(item);
                
                // Add drag listeners
                item.addEventListener('dragstart', handleParamDragStart);
                item.addEventListener('dragend', handleParamDragEnd);
            });
        }

        function updateEditParamValue(key, value) {
            const param = availableParameters[key];
            value = Math.max(param.min, Math.min(param.max, parseFloat(value)));
            currentEditParameters[key] = value;
            document.getElementById(`edit-value-${key}`).value = value;
        }

        function updateEditParamValueDirect(key, value) {
            const param = availableParameters[key];
            value = Math.max(param.min, Math.min(param.max, parseFloat(value) || param.min));
            currentEditParameters[key] = value;
            document.getElementById(`edit-param-range-${key}`).value = value;
            document.getElementById(`edit-value-${key}`).value = value;
        }

        function removeEditParam(key) {
            delete currentEditParameters[key];
            renderEditParameters();
        }

        function submitEditScenario() {
            const name = document.getElementById('editScenarioName').value;
            const description = document.getElementById('editScenarioDescription').value;

            if (!name) {
                alert('กรุณากรอกชื่อ Scenario');
                return;
            }

            fetch(`/sandbox/experiments/${experimentId}/scenarios/${currentEditScenarioId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: name,
                        description: description,
                        parameters: currentEditParameters
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาด');
                });
        }

        // Delete Scenario
        function deleteScenario(scenarioId) {
            if (!confirm('ลบ scenario นี้?')) return;

            fetch(`/sandbox/experiments/${experimentId}/scenarios/${scenarioId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาด');
                });
        }

        // Drag and Drop - Sidebar to Scenario Cards
        document.addEventListener('DOMContentLoaded', function() {
            setupSidebarDragDrop();
            setupSidebarAsDeleteZone();
            setupScenarioCardsDragDrop();
            setupEditModalDragDrop();
            setupAddModalDragDrop();
            setupInlineParameterEditing();
        });

        // Setup inline parameter editing in main page
        function setupInlineParameterEditing() {
            // Use event delegation for dynamically added elements
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('param-value-editable-inline')) {
                    const input = e.target;
                    const scenarioId = input.dataset.scenarioId;
                    const paramKey = input.dataset.paramKey;
                    const newValue = parseFloat(input.value);
                    
                    // Validate value
                    const min = parseFloat(input.min);
                    const max = parseFloat(input.max);
                    const validValue = Math.max(min, Math.min(max, newValue));
                    
                    if (newValue !== validValue) {
                        input.value = validValue;
                    }
                    
                    // Update parameter via API
                    updateParameterValue(scenarioId, paramKey, validValue);
                }
            });

            // Add visual feedback on focus
            document.addEventListener('focus', function(e) {
                if (e.target.classList.contains('param-value-editable-inline')) {
                    e.target.select(); // Select all text when focused
                }
            }, true);
        }

        // Update parameter value in scenario
        function updateParameterValue(scenarioId, paramKey, value) {
            showLoading();
            
            fetch(`/sandbox/experiments/${experimentId}/scenarios/${scenarioId}/parameter`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    param_key: paramKey,
                    value: value
                })
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    // Show success feedback (optional)
                    console.log('Parameter updated successfully');
                } else {
                    alert('Error: ' + (data.message || 'ไม่สามารถอัพเดทค่าได้'));
                    location.reload(); // Reload to restore original value
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                alert('เกิดข้อผิดพลาดในการอัพเดทค่า');
                location.reload();
            });
        }

        // Loading indicator functions
        function showLoading(message = 'กำลังบันทึกข้อมูล...') {
            const overlay = document.getElementById('loadingOverlay');
            const text = overlay.querySelector('p');
            text.textContent = message;
            overlay.classList.add('active');
        }

        function hideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            overlay.classList.remove('active');
        }

        // Setup sidebar as delete zone
        function setupSidebarAsDeleteZone() {
            const sidebar = document.querySelector('.sidebar');
            
            sidebar.addEventListener('dragover', function(e) {
                // Only allow drop for parameters (not from sidebar itself)
                const data = e.dataTransfer.types;
                if (data.includes('application/json') || data.includes('text/html')) {
                    e.preventDefault();
                    this.classList.add('drag-over-delete');
                }
            });
            
            sidebar.addEventListener('dragleave', function(e) {
                this.classList.remove('drag-over-delete');
            });
            
            sidebar.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('drag-over-delete');
                
                // Handle drop from scenario cards (existing parameters)
                try {
                    const jsonData = e.dataTransfer.getData('application/json');
                    if (jsonData) {
                        const data = JSON.parse(jsonData);
                        
                        if (data.type === 'existing-param') {
                            // ลบทันทีโดยไม่ต้องยืนยัน
                            showLoading('กำลังลบพารามิเตอร์...');
                            
                            fetch(`/sandbox/experiments/${experimentId}/scenarios/${data.scenarioId}/parameter/${data.paramKey}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                }
                            })
                            .then(res => res.json())
                            .then(result => {
                                if (result.success) {
                                    location.reload();
                                } else {
                                    hideLoading();
                                    alert('Error: ' + result.message);
                                }
                            })
                            .catch(err => {
                                hideLoading();
                                console.error(err);
                                alert('เกิดข้อผิดพลาด');
                            });
                            return;
                        }
                    }
                } catch (e) {}
                
                // Handle drop from modal
                if (currentDraggedParam && currentModalContext) {
                    if (currentModalContext === 'add') {
                        delete currentAddParameters[currentDraggedParam];
                        renderAddParameters();
                    } else if (currentModalContext === 'edit') {
                        delete currentEditParameters[currentDraggedParam];
                        renderEditParameters();
                    }
                }
            });
        }

        // Setup drag from sidebar parameters
        function setupSidebarDragDrop() {
            const paramItems = document.querySelectorAll('.parameter-item');

            paramItems.forEach(item => {
                item.addEventListener('dragstart', function(e) {
                    e.dataTransfer.setData('text/plain', this.dataset.param);
                    e.dataTransfer.effectAllowed = 'copy';
                    this.style.opacity = '0.5';
                    
                    // Store current dragging param globally for dragover check
                    window.currentDraggingParam = this.dataset.param;
                });

                item.addEventListener('dragend', function(e) {
                    this.style.opacity = '1';
                    window.currentDraggingParam = null;
                });
            });
        }

        // Setup drop zones in scenario cards
        function setupScenarioCardsDragDrop() {
            const scenarioCards = document.querySelectorAll('.scenario-card:not(.baseline)');
            const paramDisplays = document.querySelectorAll('.param-display[draggable="true"]');

            // Make entire scenario card a drop zone
            scenarioCards.forEach(card => {
                const scenarioId = card.dataset.scenarioId;
                
                card.addEventListener('dragover', function(e) {
                    const paramKey = window.currentDraggingParam;
                    
                    if (!paramKey) {
                        return;
                    }
                    
                    // ตรวจสอบว่ามีพารามิเตอร์ซ้ำหรือไม่
                    const existingParams = document.querySelectorAll(`[data-scenario-id="${scenarioId}"][data-param-key="${paramKey}"]`);
                    
                    if (existingParams.length > 0) {
                        // ห้ามวาง - แสดง cursor ห้าม
                        e.dataTransfer.dropEffect = 'none';
                        this.classList.add('duplicate-error');
                        existingParams.forEach(el => el.classList.add('duplicate-warning'));
                    } else {
                        // อนุญาตให้วาง
                        e.preventDefault();
                        this.classList.add('drag-over');
                    }
                });

                card.addEventListener('dragleave', function(e) {
                    // Check if we're actually leaving the card (not just moving between children)
                    if (!this.contains(e.relatedTarget)) {
                        this.classList.remove('drag-over', 'duplicate-error');
                        const existingParams = this.querySelectorAll('.param-display.duplicate-warning');
                        existingParams.forEach(el => el.classList.remove('duplicate-warning'));
                    }
                });

                card.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('drag-over', 'duplicate-error');
                    
                    // Clear any warning animations
                    const existingParams = this.querySelectorAll('.param-display.duplicate-warning');
                    existingParams.forEach(el => el.classList.remove('duplicate-warning'));

                    const paramKey = e.dataTransfer.getData('text/plain');

                    if (!paramKey || !availableParameters[paramKey]) {
                        return;
                    }

                    // ตรวจสอบซ้ำอีกครั้งก่อน drop (double check)
                    const duplicateCheck = document.querySelectorAll(`[data-scenario-id="${scenarioId}"][data-param-key="${paramKey}"]`);
                    if (duplicateCheck.length > 0) {
                        // ห้ามวาง - ไม่ทำอะไร
                        return;
                    }

                    const defaultVal = availableParameters[paramKey].default || availableParameters[paramKey].min;

                    // Add parameter via AJAX
                    showLoading('กำลังเพิ่มพารามิเตอร์...');
                    
                    fetch(`/sandbox/experiments/${experimentId}/scenarios/${scenarioId}/parameter`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                param_key: paramKey,
                                value: defaultVal
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                location.reload(); // Reload to show new parameter
                            } else {
                                hideLoading();
                                alert('Error: ' + (data.message || 'ไม่สามารถเพิ่มพารามิเตอร์ได้'));
                            }
                        })
                        .catch(err => {
                            hideLoading();
                            console.error(err);
                            alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                        });
                });
            });

            // Drag existing parameters to delete zone
            paramDisplays.forEach(item => {
                item.addEventListener('dragstart', function(e) {
                    const paramKey = this.dataset.paramKey;
                    const scenarioId = this.dataset.scenarioId;
                    
                    e.dataTransfer.setData('application/json', JSON.stringify({
                        type: 'existing-param',
                        paramKey: paramKey,
                        scenarioId: scenarioId
                    }));
                    
                    this.style.opacity = '0.5';
                    
                    // Highlight sidebar as delete zone
                    const sidebar = document.querySelector('.sidebar');
                    sidebar.style.transition = 'all 0.3s';
                });

                item.addEventListener('dragend', function(e) {
                    this.style.opacity = '1';
                    
                    // Remove sidebar highlight
                    const sidebar = document.querySelector('.sidebar');
                    sidebar.classList.remove('drag-over-delete');
                });
            });
        }

        function setupAddModalDragDrop() {
            const addDropZone = document.getElementById('addDropZone');

            // Make parameters in add modal draggable
            document.addEventListener('shown.bs.modal', function(e) {
                if (e.target.id === 'addScenarioModal') {
                    const dragItems = document.querySelectorAll('#addAvailableParams .param-drag-item');

                    dragItems.forEach(item => {
                        item.addEventListener('dragstart', function(e) {
                            e.dataTransfer.setData('text/plain', this.dataset.paramKey);
                            this.style.opacity = '0.5';
                        });

                        item.addEventListener('dragend', function(e) {
                            this.style.opacity = '1';
                        });
                    });
                }
            });

            // Drop zone handlers for Add Modal
            addDropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('drag-over');
            });

            addDropZone.addEventListener('dragleave', function(e) {
                this.classList.remove('drag-over');
            });

            addDropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('drag-over');

                const paramKey = e.dataTransfer.getData('text/plain');

                if (!paramKey || !availableParameters[paramKey]) {
                    alert('พารามิเตอร์ไม่ถูกต้อง');
                    return;
                }

                // Check if already exists
                if (currentAddParameters[paramKey]) {
                    alert('มีพารามิเตอร์นี้อยู่แล้ว');
                    return;
                }

                // Add with default value
                const defaultValue = availableParameters[paramKey].default || availableParameters[paramKey].min;
                currentAddParameters[paramKey] = defaultValue;

                // Re-render
                renderAddParameters();
            });
        }

        function setupEditModalDragDrop() {
            const editDropZone = document.getElementById('editDropZone');

            // Make parameters in edit modal draggable
            document.addEventListener('shown.bs.modal', function(e) {
                if (e.target.id === 'editScenarioModal') {
                    const dragItems = document.querySelectorAll('#editAvailableParams .param-drag-item');

                    dragItems.forEach(item => {
                        item.addEventListener('dragstart', function(e) {
                            e.dataTransfer.setData('text/plain', this.dataset.paramKey);
                            this.style.opacity = '0.5';
                        });

                        item.addEventListener('dragend', function(e) {
                            this.style.opacity = '1';
                        });
                    });
                }
            });

            // Drop zone handlers
            editDropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('drag-over');
            });

            editDropZone.addEventListener('dragleave', function(e) {
                this.classList.remove('drag-over');
            });

            editDropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('drag-over');

                const paramKey = e.dataTransfer.getData('text/plain');

                if (!paramKey || !availableParameters[paramKey]) {
                    alert('พารามิเตอร์ไม่ถูกต้อง');
                    return;
                }

                // Check if already exists
                if (currentEditParameters[paramKey]) {
                    alert('มีพารามิเตอร์นี้อยู่แล้ว');
                    return;
                }

                // Add with default value
                const defaultValue = availableParameters[paramKey].default || availableParameters[paramKey].min;
                currentEditParameters[paramKey] = defaultValue;

                // Re-render
                renderEditParameters();
            });
        }

        // Drag to Delete Handlers
        let currentDraggedParam = null;
        let currentModalContext = null; // 'add' or 'edit'

        function handleParamDragStart(e) {
            currentDraggedParam = this.dataset.paramKey;
            
            // Determine which modal is open
            const addModal = document.getElementById('addScenarioModal');
            const editModal = document.getElementById('editScenarioModal');
            
            if (addModal.classList.contains('show')) {
                currentModalContext = 'add';
            } else if (editModal.classList.contains('show')) {
                currentModalContext = 'edit';
            }
            
            this.style.opacity = '0.5';
            
            // Show delete zone
            const deleteZone = document.getElementById('deleteZone');
            deleteZone.classList.add('active');
            
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.innerHTML);
        }

        function handleParamDragEnd(e) {
            this.style.opacity = '1';
            
            // Hide delete zone
            const deleteZone = document.getElementById('deleteZone');
            deleteZone.classList.remove('active');
            deleteZone.classList.remove('drag-over');
            
            currentDraggedParam = null;
            currentModalContext = null;
        }

        // Setup Delete Zone
        document.addEventListener('DOMContentLoaded', function() {
            const deleteZone = document.getElementById('deleteZone');
            
            deleteZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('drag-over');
            });
            
            deleteZone.addEventListener('dragleave', function(e) {
                this.classList.remove('drag-over');
            });
            
            deleteZone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('drag-over');
                
                if (currentDraggedParam) {
                    // Remove from appropriate context
                    if (currentModalContext === 'add') {
                        delete currentAddParameters[currentDraggedParam];
                        renderAddParameters();
                    } else if (currentModalContext === 'edit') {
                        delete currentEditParameters[currentDraggedParam];
                        renderEditParameters();
                    }
                    
                    // Show feedback
                    const icon = this.querySelector('i');
                    icon.className = 'fas fa-check';
                    setTimeout(() => {
                        icon.className = 'fas fa-trash-alt';
                    }, 500);
                }
            });
        });
    </script>
@endsection
