<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- ======================================================= -->
        <!-- TAMBAHAN WAJIB: BOOTSTRAP 5 CSS & ICONS -->
        <!-- ======================================================= -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

        <!-- Scripts (Bawaan Laravel Breeze / Tailwind) -->
        <!-- Note: Tailwind mungkin akan sedikit bentrok dengan Bootstrap, tapi biarkan dulu -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Custom Style untuk menimpa konflik Tailwind jika perlu -->
        <style>
            /* Hapus underline default pada link di Bootstrap jika bentrok dengan Tailwind */
            a { text-decoration: none; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>

    @include('layouts.footer')
        <!-- ======================================================= -->
        <!-- TAMBAHAN WAJIB: BOOTSTRAP 5 JS BUNDLE -->
        <!-- ======================================================= -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- SweetAlert2 library -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Custom script to convert standard confirm() dialogs to beautiful centered SweetAlert2 modals -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Function to replace confirm prompts on form submit
                function initFormConfirmations() {
                    document.querySelectorAll('form').forEach(form => {
                        const onsubmitAttr = form.getAttribute('onsubmit');
                        if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                            const match = onsubmitAttr.match(/confirm\(['"](.+?)['"]\)/);
                            if (match) {
                                const message = match[1];
                                form.removeAttribute('onsubmit');
                                
                                let confirmed = false;
                                form.addEventListener('submit', function(e) {
                                    if (confirmed) return;
                                    e.preventDefault();
                                    
                                    Swal.fire({
                                        title: 'Konfirmasi Tindakan',
                                        text: message,
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#aaa',
                                        confirmButtonText: 'Ya, Lanjutkan',
                                        cancelButtonText: 'Batal',
                                        customClass: {
                                            popup: 'rounded-4 shadow'
                                        }
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            confirmed = true;
                                            form.submit();
                                        }
                                    });
                                });
                            }
                        }
                    });

                    // Function to replace confirm prompts on button/link click
                    document.querySelectorAll('[onclick]').forEach(el => {
                        const onclickAttr = el.getAttribute('onclick');
                        if (onclickAttr && onclickAttr.includes('confirm(')) {
                            const match = onclickAttr.match(/confirm\(['"](.+?)['"]\)/);
                            if (match) {
                                const message = match[1];
                                el.removeAttribute('onclick');
                                el.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    
                                    Swal.fire({
                                        title: 'Konfirmasi Tindakan',
                                        text: message,
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#aaa',
                                        confirmButtonText: 'Ya, Lanjutkan',
                                        cancelButtonText: 'Batal',
                                        customClass: {
                                            popup: 'rounded-4 shadow'
                                        }
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            const form = el.closest('form');
                                            if (form) {
                                                form.submit();
                                            } else if (el.tagName === 'A') {
                                                window.location.href = el.getAttribute('href');
                                            }
                                        }
                                    });
                                });
                            }
                        }
                    });
                }

                initFormConfirmations();

                // Re-run for dynamic forms inside modals if they are initialized/rendered
                const observer = new MutationObserver(function(mutations) {
                    initFormConfirmations();
                });
                observer.observe(document.body, { childList: true, subtree: true });
            });
        </script>
    </body>
</html>
