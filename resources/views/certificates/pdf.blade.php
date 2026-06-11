<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat - {{ $certificate->user->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Georgia', serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .controls {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print {
            background: #4F46E5;
            color: white;
        }

        .btn-print:hover {
            background: #4338CA;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-download {
            background: #10B981;
            color: white;
        }

        .btn-download:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-back {
            background: #6B7280;
            color: white;
        }

        .btn-back:hover {
            background: #4B5563;
            transform: translateY(-2px);
        }

        .certificate-wrapper {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .certificate-content {
            text-align: center;
            padding: 80px 60px;
            background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%);
            border: 3px solid #4F46E5;
            margin: 20px;
            position: relative;
        }

        .certificate-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="10" cy="10" r="2" fill="rgba(79,70,229,0.05)"/><circle cx="90" cy="90" r="2" fill="rgba(79,70,229,0.05)"/></svg>');
            pointer-events: none;
        }

        .certificate-content > * {
            position: relative;
            z-index: 1;
        }

        .title { 
            font-size: 44px; 
            color: #4F46E5; 
            margin-bottom: 8px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .subtitle { 
            font-size: 20px; 
            color: #6366F1; 
            margin-bottom: 50px;
            font-weight: 500;
        }

        .intro {
            font-size: 16px;
            color: #374151;
            margin-bottom: 20px;
            font-style: italic;
        }

        .name { 
            font-size: 38px; 
            font-weight: bold; 
            color: #1F2937; 
            border-bottom: 3px solid #4F46E5; 
            display: inline-block; 
            padding-bottom: 12px; 
            margin-bottom: 30px;
            min-width: 400px;
        }

        .achievement {
            font-size: 16px;
            color: #374151;
            margin-bottom: 15px;
        }

        .course { 
            font-size: 28px; 
            color: #4F46E5;
            margin-bottom: 50px;
            font-weight: bold;
        }

        .footer-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-top: 60px;
            padding-top: 40px;
            border-top: 2px solid #E5E7EB;
        }

        .footer-item {
            text-align: center;
        }

        .footer-label {
            font-size: 12px;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .date { 
            font-size: 18px; 
            color: #374151; 
            font-weight: 600;
        }

        .code { 
            font-size: 13px; 
            color: #9CA3AF; 
            letter-spacing: 1px;
            word-break: break-all;
        }

        @media (max-width: 768px) {
            .certificate-content {
                padding: 40px 30px;
                margin: 10px;
            }

            .name {
                font-size: 24px;
                min-width: auto;
            }

            .title {
                font-size: 28px;
            }

            .course {
                font-size: 20px;
            }

            .footer-section {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .controls {
                gap: 8px;
            }

            .btn {
                padding: 8px 16px;
                font-size: 12px;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .controls {
                display: none;
            }

            .certificate-wrapper {
                box-shadow: none;
                border-radius: 0;
            }

            .certificate-content {
                border: 3px solid #4F46E5;
                margin: 0;
                padding: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Control Buttons --}}
        <div class="controls">
            <button class="btn btn-print" onclick="window.print()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print / Download PDF
            </button>
            <button class="btn btn-back" onclick="history.back()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Kembali
            </button>
        </div>

        {{-- Certificate --}}
        <div class="certificate-wrapper">
            <div class="certificate-content">
                <div class="title">Sertifikat Keahlian</div>
                <div class="subtitle">Learn Everything Platform</div>
                
                <p class="intro">Dengan bangga kami persembahkan</p>
                
                <div class="name">{{ $certificate->user->name }}</div>
                
                <p class="achievement">telah berhasil menyelesaikan kursus</p>
                
                <div class="course">{{ $certificate->enrollment->course->title }}</div>

                <div class="footer-section">
                    <div class="footer-item">
                        <div class="footer-label">Tanggal Penerbitan</div>
                        <div class="date">{{ $certificate->issued_at->format('d F Y') }}</div>
                    </div>
                    <div class="footer-item">
                        <div class="footer-label">Nomor Sertifikat</div>
                        <div class="code">{{ $certificate->certificate_number }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Optional: Uncomment to trigger print dialog automatically on page load
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>
</html>