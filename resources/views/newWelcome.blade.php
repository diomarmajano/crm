<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'crmcloud') }} - Sistema de Venta e Inventario</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    theme: {
                        extend: {
                            colors: {
                                primary: '#5C64F2',
                                'primary-hover': '#4A51D1',
                                'primary-light': '#EEF0FF',
                            },
                            fontFamily: {
                                sans: ['Instrument Sans', 'sans-serif'],
                            }
                        }
                    }
                }
            </script>
        @endif

        <style>
            .reveal {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
            }
            .reveal.active {
                opacity: 1;
                transform: translateY(0);
            }
            .delay-100 { transition-delay: 100ms; }
            .delay-200 { transition-delay: 200ms; }
            .delay-300 { transition-delay: 300ms; }
        </style>
    </head>
    <body class="font-sans antialiased bg-white text-gray-800 selection:bg-primary selection:text-white">

        <nav class="fixed w-full z-50 top-0 bg-white/80 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-2 cursor-pointer hover:opacity-80 transition-opacity">
                        <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-[#4A51D1] font-bold text-xl shadow-sm shadow-primary/50">
                            C
                        </div>
                        <span class="font-bold text-xl tracking-tight text-gray-900">rm<span class="text-primary">cloud</span></span>
                    </div>
                    <div class="hidden md:flex space-x-8">
                        <a href="#caracteristicas" class="text-sm font-medium text-gray-600 hover:text-primary transition-colors">Características</a>
                        <a href="#equipamiento" class="text-sm font-medium text-gray-600 hover:text-primary transition-colors">Equipamiento</a>
                        <a href="#precio" class="text-sm font-medium text-gray-600 hover:text-primary transition-colors">Precio</a>
                        <a href="#faq" class="text-sm font-medium text-gray-600 hover:text-primary transition-colors">FAQ</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/login" class="text-sm font-medium text-gray-600 hover:text-primary transition-colors">¿Te interesa?</a>
                        <a href="#contacto" class="bg-[#4A51D1] hover:bg-primary-hover text-white px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 shadow-md shadow-primary/30 hover:shadow-lg hover:shadow-primary/40 hover:-translate-y-0.5">
                            Prueba gratuita
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <section class="pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden relative">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-primary-light rounded-full blur-3xl opacity-50 -z-10 transform -translate-y-1/2"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="reveal">
                    <span class="inline-block py-1 px-3 rounded-full bg-primary-light text-primary text-sm font-medium mb-6 border border-primary/20">
                        Especial para Almacenes y negocios de venta presencial.
                    </span>
                </div>
                <h1 class="reveal delay-100 text-4xl md:text-6xl font-bold text-gray-900 mb-6 tracking-tight leading-tight">
                    Controla tus ventas e inventario <br class="hidden md:block" /> con precisión milimétrica
                </h1>
                <p class="reveal delay-200 mt-4 text-lg text-gray-600 max-w-2xl mx-auto mb-10">
                    El sistema post-venta todo en uno. Gestiona productos, visualiza estadísticas en tiempo real y agiliza tus cobros. Software + Equipamiento incluido.
                </p>
                <div class="reveal delay-300 flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="#precio" class="w-full sm:w-auto bg-[#4A51D1] hover:bg-primary-hover text-white px-8 py-3.5 rounded-full text-base font-semibold transition-all duration-300 shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40 hover:-translate-y-1">
                        Ver plan mensual
                    </a>
                    <a href="#caracteristicas" class="w-full sm:w-auto bg-white border border-gray-200 hover:border-primary hover:text-primary text-gray-700 px-8 py-3.5 rounded-full text-base font-semibold transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-1">
                        Explorar módulos
                    </a>
                </div>

                <div class="reveal delay-300 mt-16 relative max-w-5xl mx-auto group">
                    <div class="rounded-2xl border border-gray-200 bg-white shadow-2xl overflow-hidden transition-transform duration-500 group-hover:scale-[1.01]">
                        <div class="bg-gray-50 border-b border-gray-100 px-4 py-3 flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50/50">
                            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                <p class="text-sm text-gray-500 mb-1">Ventas del Día</p>
                                <p class="text-2xl font-bold text-gray-900">$1.250.000</p>
                                <p class="text-xs text-green-500 mt-2 font-medium">↑ 12% vs ayer</p>
                            </div>
                            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                <p class="text-sm text-gray-500 mb-1">Productos con Stock Mínimo</p>
                                <p class="text-2xl font-bold text-primary">14</p>
                                <p class="text-xs text-gray-400 mt-2">Requieren reposición urgente</p>
                            </div>
                            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                <p class="text-sm text-gray-500 mb-1">Total Pedidos</p>
                                <p class="text-2xl font-bold text-gray-900">86</p>
                                <p class="text-xs text-gray-400 mt-2">Efectivo, Transf. y Tarjeta</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="caracteristicas" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Todo lo que tu negocio necesita en un solo lugar</h2>
                    <p class="text-gray-600">Seis módulos diseñados para cubrir cada aspecto operativo de tu almacén o local.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="reveal bg-white p-8 rounded-2xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-xl hover:border-primary/20 hover:-translate-y-2 group">
                        <div class="w-12 h-12 bg-primary-light text-primary rounded-xl flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110 group-hover:bg-primary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors">1. Escritorio (Dashboard)</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Visualiza estadísticas clave al instante: cantidad y total de ventas, desglose por método de pago (efectivo, transferencia, débito/crédito), productos con stock mínimo y gráficas de ventas por día.
                        </p>
                    </div>

                    <div class="reveal delay-100 bg-white p-8 rounded-2xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-xl hover:border-primary/20 hover:-translate-y-2 group">
                        <div class="w-12 h-12 bg-primary-light text-primary rounded-xl flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110 group-hover:bg-primary ">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors">2. Punto de Venta ágil</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Buscador rápido por código de barras o nombre. Productos en formato de "cards" clickeables que se suman al resumen del pedido. Cobra fácilmente seleccionando el medio de pago.
                        </p>
                    </div>

                    <div class="reveal delay-200 bg-white p-8 rounded-2xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-xl hover:border-primary/20 hover:-translate-y-2 group">
                        <div class="w-12 h-12 bg-primary-light text-primary rounded-xl flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110 group-hover:bg-primary ">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors">3. Historial de Pedidos</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Mantén el registro de todas las transacciones. Visualiza la sucursal, el vendedor, monto pagado, fecha y filtra rápidamente según el método de pago utilizado.
                        </p>
                    </div>

                    <div class="reveal bg-white p-8 rounded-2xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-xl hover:border-primary/20 hover:-translate-y-2 group">
                        <div class="w-12 h-12 bg-primary-light text-primary rounded-xl flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110 group-hover:bg-primary ">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors">4. Catálogo de Productos</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Crea productos con todo detalle: nombre, precio, promociones, categoría, URL de imagen, fecha de vencimiento, código de barra y SKU.
                        </p>
                    </div>

                    <div class="reveal delay-100 bg-white p-8 rounded-2xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-xl hover:border-primary/20 hover:-translate-y-2 group">
                        <div class="w-12 h-12 bg-primary-light text-primary rounded-xl flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110 group-hover:bg-primary ">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors">5. Gestión de Categorías</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Organiza tu negocio. Crea categorías personalizadas fácilmente para luego asociarlas a tus productos y mantener el orden en tu punto de venta.
                        </p>
                    </div>

                    <div class="reveal delay-200 bg-white p-8 rounded-2xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-xl hover:border-primary/20 hover:-translate-y-2 group">
                        <div class="w-12 h-12 bg-primary-light text-primary rounded-xl flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110 group-hover:bg-primary ">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors">6. Control de Inventario</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Configura el stock actual, stock mínimo para alertas, precio de compra y precio de venta. El sistema calcula automáticamente el margen de ganancia por producto.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="equipamiento" class="py-20 bg-gray-900 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-primary to-transparent"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col lg:flex-row items-center gap-12">
                    <div class="lg:w-1/2 reveal">
                        <span class="text-primary-light font-medium tracking-wider uppercase text-sm mb-2 block">Ventaja Exclusiva</span>
                        <h2 class="text-3xl md:text-4xl font-bold mb-6 leading-tight">Hardware listo para operar incluido en tu plan</h2>
                        <p class="text-gray-300 text-lg mb-8">
                            No te preocupes por comprar equipos costosos. Con crmcloud te entregamos las herramientas necesarias para que empieces a vender de inmediato.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 transition-transform hover:translate-x-2 duration-300">
                                <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center text-primary-light shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-gray-200 font-medium">Lector de código de barras de alta velocidad.</span>
                            </li>
                            <li class="flex items-center gap-3 transition-transform hover:translate-x-2 duration-300">
                                <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center text-primary-light shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-gray-200 font-medium">Impresora térmica para comprobantes y boletas.</span>
                            </li>
                        </ul>
                    </div>
                    <div class="lg:w-1/2 w-full reveal delay-200">
                        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-8 flex justify-center items-center shadow-2xl h-80 relative group hover:border-gray-600 transition-colors duration-500">
                            <div class="text-center relative z-10">
                                <p class="text-gray-400 font-medium uppercase tracking-widest text-sm mb-6 group-hover:text-primary-light transition-colors">Pack de Equipamiento</p>
                                <div class="flex justify-center gap-6">
                                    <div class="w-24 h-24 bg-gray-700 rounded-xl flex items-center justify-center text-gray-300 shadow-inner transition-transform duration-500 hover:scale-110 hover:text-white hover:bg-primary/50">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <rect x="4" y="7" width="16" height="10" rx="1.5" ry="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M7 9v6M9 9v6M12 9v6M14 9v6M17 9v6" />
                                        </svg>

                                    </div>
                                    <div class="w-24 h-24 bg-gray-700 rounded-xl flex items-center justify-center text-gray-300 shadow-inner transition-transform duration-500 hover:scale-110 hover:text-white hover:bg-primary/50">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="precio" class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Un plan único, sin complicaciones</h2>
                    <p class="text-gray-600">Obtén el software completo y las herramientas físicas por una sola tarifa mensual.</p>
                </div>

                <div class="reveal delay-200 max-w-lg mx-auto bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden relative transition-all duration-500 hover:shadow-2xl hover:-translate-y-2">
                    <div class="h-2 bg-primary w-full"></div>
                    <div class="p-8 md:p-12 relative">
                        <div class="absolute top-0 right-0 p-4 opacity-5">
                            <svg class="w-24 h-24 text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2z"/></svg>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Plan crmcloud</h3>
                        <div class="flex justify-center items-baseline my-6">
                            <span class="text-5xl font-extrabold text-gray-900">$30.000</span>
                            <span class="text-gray-500 ml-2 font-medium">/mes + IVA</span>
                        </div>
                        
                        <ul class="space-y-4 mb-8 mt-8">
                            <li class="flex items-start gap-3 group">
                                <svg class="w-5 h-5 text-primary shrink-0 mt-0.5 transition-transform group-hover:scale-125 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-600">Acceso a todos los módulos del software (Dashboard, Inventario, POS, etc.)</span>
                            </li>
                            <li class="flex items-start gap-3 group">
                                <svg class="w-5 h-5 text-primary shrink-0 mt-0.5 transition-transform group-hover:scale-125 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-600">Lector de código de barras incluido</span>
                            </li>
                            <li class="flex items-start gap-3 group">
                                <svg class="w-5 h-5 text-primary shrink-0 mt-0.5 transition-transform group-hover:scale-125 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-600">Impresora térmica incluido</span>
                            </li>
                            <li class="flex items-start gap-3 group">
                                <svg class="w-5 h-5 text-primary shrink-0 mt-0.5 transition-transform group-hover:scale-125 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-gray-600">Soporte técnico y actualizaciones</span>
                            </li>
                        </ul>

                        <a href="#contacto" class="block w-full bg-[#4A51D1] hover:bg-primary-hover text-white text-center px-6 py-4 rounded-xl text-lg font-semibold transition-all duration-300 shadow-md shadow-primary/30 hover:shadow-lg hover:-translate-y-1">
                            Contratar Plan
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section id="faq" class="py-20 bg-gray-50 border-t border-gray-100">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 reveal">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Preguntas Frecuentes</h2>
                    <p class="text-gray-600">Resolvemos las dudas más comunes sobre nuestro servicio y equipos.</p>
                </div>

                <div class="space-y-4">
                    <div class="reveal bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md hover:border-primary/30 transition-all duration-300">
                        <h4 class="text-lg font-bold text-gray-900 mb-2">¿Qué pasa si una de las máquinas falla?</h4>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            El arriendo incluye garantía ante fallas de fábrica o desgaste por uso normal. Si tu lector de códigos o impresora presenta problemas, nos contactas y gestionamos el reemplazo para que tu negocio no pare de vender.
                        </p>
                    </div>

                    <div class="reveal delay-100 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md hover:border-primary/30 transition-all duration-300">
                        <h4 class="text-lg font-bold text-gray-900 mb-2">¿Necesito tener un computador muy moderno?</h4>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            No. Como crmcloud es un sistema basado en la nube (SaaS), solo necesitas un computador (o incluso una tablet) con conexión a internet y un navegador web. El sistema es sumamente ligero y rápido.
                        </p>
                    </div>

                    <div class="reveal delay-200 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md hover:border-primary/30 transition-all duration-300">
                        <h4 class="text-lg font-bold text-gray-900 mb-2">¿Hay un tiempo mínimo de contrato?</h4>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            No te amarramos con contratos a largo plazo. Pagas tu mensualidad de $30.000 + IVA mes a mes. Si en algún momento decides cancelar, solo debes devolver los equipos en buen estado y listo.
                        </p>
                    </div>

                    <div class="reveal delay-300 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md hover:border-primary/30 transition-all duration-300">
                        <h4 class="text-lg font-bold text-gray-900 mb-2">¿Cómo se instalan la impresora y el lector?</h4>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Ambos equipos son "Plug and Play" (conectar y usar) vía USB. Te entregamos un manual rápido de un par de pasos, y si tienes dudas, nuestro soporte técnico te ayuda en la configuración inicial de forma remota.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="contacto" class="py-24 bg-white relative">
            <div class="absolute top-0 right-0 w-1/2 h-full bg-primary-light/30 rounded-l-full blur-3xl opacity-50 -z-10"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="reveal">
                        <span class="text-primary font-semibold tracking-wider uppercase text-sm mb-2 block">Comencemos</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">¿Listo para modernizar tu negocio?</h2>
                        <p class="text-gray-600 text-lg mb-8">
                            Déjanos tus datos y nos pondremos en contacto contigo para coordinar la entrega de tus equipos y la activación de tu cuenta crmcloud.
                        </p>
                        
                        <div class="flex items-center gap-4 mb-6 group cursor-pointer">
                            <div class="w-12 h-12 bg-primary-light text-primary rounded-full flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-[#4A51D1] group-hover:text-white group-hover:scale-110 group-hover:shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Llámanos o escríbenos por WhatsApp</p>
                                <p class="text-lg font-bold text-gray-900 group-hover:text-[#4A51D1] transition-colors">+56 9 1234 5678</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 group cursor-pointer">
                            <div class="w-12 h-12 bg-primary-light text-primary rounded-full flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-[#4A51D1] group-hover:text-white group-hover:scale-110 group-hover:shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Correo de soporte y ventas</p>
                                <p class="text-lg font-bold text-gray-900 group-hover:text-[#4A51D1] transition-colors">info@ifcloud.cl</p>
                            </div>
                        </div>
                    </div>

                    <div class="reveal delay-200 bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-100 p-8 md:p-10 transition-transform duration-500 hover:shadow-[0_15px_40px_rgb(0,0,0,0.12)]">
                        <form action="#" method="POST" class="space-y-5">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Tu Nombre</label>
                                    <input type="text" id="nombre" name="nombre" class="w-full rounded-xl border-gray-300 border px-4 py-3 text-sm focus:border-primary focus:ring-primary focus:ring-2 focus:ring-opacity-50 outline-none transition-all duration-300" placeholder="Ej. Carmen Arteaga" required>
                                </div>
                                <div>
                                    <label for="local" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Local</label>
                                    <input type="text" id="local" name="local" class="w-full rounded-xl border-gray-300 border px-4 py-3 text-sm focus:border-primary focus:ring-primary focus:ring-2 focus:ring-opacity-50 outline-none transition-all duration-300" placeholder="Ej. Minimarket Los Menas" required>
                                </div>
                            </div>
                            <div>
                                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono / WhatsApp</label>
                                <input type="tel" id="telefono" name="telefono" class="w-full rounded-xl border-gray-300 border px-4 py-3 text-sm focus:border-primary focus:ring-primary focus:ring-2 focus:ring-opacity-50 outline-none transition-all duration-300" placeholder="+56 9 0000 0000" required>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                                <input type="email" id="email" name="email" class="w-full rounded-xl border-gray-300 border px-4 py-3 text-sm focus:border-primary focus:ring-primary focus:ring-2 focus:ring-opacity-50 outline-none transition-all duration-300" placeholder="correo@ejemplo.com" required>
                            </div>
                            <div>
                                <label for="mensaje" class="block text-sm font-medium text-gray-700 mb-1">Mensaje (Opcional)</label>
                                <textarea id="mensaje" name="mensaje" rows="3" class="w-full rounded-xl border-gray-300 border px-4 py-3 text-sm focus:border-primary focus:ring-primary focus:ring-2 focus:ring-opacity-50 outline-none transition-all duration-300 resize-none" placeholder="¿Tienes alguna duda específica sobre la entrega o el software?"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-[#4A51D1] hover:bg-primary-hover text-white px-6 py-4 rounded-xl text-base font-semibold transition-all duration-300 shadow-md shadow-primary/30 hover:shadow-lg hover:-translate-y-1 mt-2">
                                Solicitar contratación
                            </button>
                            <p class="text-xs text-gray-400 text-center mt-4">Al enviar este formulario, un ejecutivo te contactará a la brevedad.</p>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <footer class="bg-gray-900 pt-16 pb-8 border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                    <div class="col-span-1 md:col-span-2 lg:col-span-1 reveal">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded bg-[#4A51D1] flex items-center justify-center text-white font-bold text-lg">
                                C
                            </div>
                            <span class="font-bold text-2xl tracking-tight text-white">rm<span class="text-primary">cloud</span></span>
                        </div>
                        <p class="text-gray-400 text-sm leading-relaxed mb-6">
                            El sistema de post-venta de <a href="https://ifcloud.cl" target="_black" class="text-primary hover:underline">ifcloud.cl</a> definitivo para almacenes y negocios físicos. Toma el control total de tu inventario y agiliza tus ventas hoy mismo.
                        </p>
                    </div>

                    <div class="reveal delay-100">
                        <h4 class="text-white font-semibold mb-4">Producto</h4>
                        <ul class="space-y-3">
                            <li><a href="#caracteristicas" class="text-gray-400 hover:text-primary text-sm transition-colors duration-300">Características</a></li>
                            <li><a href="#equipamiento" class="text-gray-400 hover:text-primary text-sm transition-colors duration-300">Equipamiento</a></li>
                            <li><a href="#precio" class="text-gray-400 hover:text-primary text-sm transition-colors duration-300">Precios</a></li>
                            <li><a href="#faq" class="text-gray-400 hover:text-primary text-sm transition-colors duration-300">Preguntas Frecuentes</a></li>
                        </ul>
                    </div>

                    <div class="reveal delay-200">
                        <h4 class="text-white font-semibold mb-4">Legal</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-gray-400 hover:text-primary text-sm transition-colors duration-300">Términos y Condiciones</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-primary text-sm transition-colors duration-300">Políticas de Privacidad</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-primary text-sm transition-colors duration-300">Garantía de Equipos</a></li>
                        </ul>
                    </div>

                    <div class="reveal delay-300">
                        <h4 class="text-white font-semibold mb-4">Soporte</h4>
                        <ul class="space-y-3 text-sm text-gray-400">
                            <li>Lun - Vie: 09:00 a 18:00</li>
                            <li><a href="mailto:info@ifcloud.cl" class="hover:text-primary transition-colors duration-300">info@ifcloud.cl</a></li>
                            <li><a href="tel:+56912345678" class="hover:text-primary transition-colors duration-300">+56 9 1234 5678</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 reveal">
                    <p class="text-gray-500 text-sm">
                        &copy; {{ date('Y') }} crmcloud. Todos los derechos reservados.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-500 hover:text-primary transition-all duration-300 hover:-translate-y-1">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-primary transition-all duration-300 hover:-translate-y-1">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const reveals = document.querySelectorAll('.reveal');

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('active');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1, 
                    rootMargin: "0px 0px -50px 0px"
                });

                reveals.forEach(reveal => {
                    observer.observe(reveal);
                });
            });
        </script>
    </body>
</html>