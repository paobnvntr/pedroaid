@extends('landing-page.layouts.app')

@section('title', 'PedroAID - Sangguniang Panlungsod')

@section('contents')
	<section class="city-ordinance d-flex align-items-center">
		<div class="container">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <h1 data-aos="fade-up">Sangguniang Panlungsod</h1>
            </div>
		</div>
	</section>

    <section class="ordinance p-5 councilor-container">
        <div class="container">
            <header class="section-header" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center text-lg-start d-flex align-items-center justify-content-between">
                    <p class="align-items-left">Members</p>
                    <a href="{{ route('home') }}"
                        class="btn-ordinance-back scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                        <i class="bi bi-arrow-left"></i>
                        <span>Go Back</span>
                    </a>
                </div>
			</header>

            <div class="row mb-4">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="councilor text-center">
                        <img src="{{ asset('images/councilor-acierto.jpg') }}" alt="Dr. Marion A. Acierto" class="img-fluid rounded-circle border border-5 border-warning mb-4">
                        <p>Hon. Dr. Marion A. Acierto</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="councilor text-center">
                        <img src="{{ asset('images/councilor-almoro.jpg') }}" alt="Sheriliz Niña B. Almoro" class="img-fluid rounded-circle border border-5 border-warning mb-4">
                        <p>Hon. Sheriliz Niña B. Almoro</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="councilor text-center">
                        <img src="{{ asset('images/councilor-ambayec.png') }}" alt="Carlon S. Ambayec" class="img-fluid rounded-circle border border-5 border-warning mb-4">
                        <p>Hon. Carlon S. Ambayec</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="councilor text-center">
                        <img src="{{ asset('images/councilor-casacop.jpg') }}" alt="Michael M. Casacop" class="img-fluid rounded-circle border border-5 border-warning mb-4">
                        <p>Hon. Michael M. Casacop</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="councilor text-center">
                        <img src="{{ asset('images/councilor-lu.jpg') }}" alt="Leslie E. Lu" class="img-fluid rounded-circle border border-5 border-warning mb-4">
                        <p>Hon. Leslie E. Lu</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="councilor text-center">
                        <img src="{{ asset('images/councilor-mendoza.jpg') }}" alt="Dr. Jose Enrico M. Mendoza" class="img-fluid rounded-circle border border-5 border-warning mb-4">
                        <p>Hon. Dr. Jose Enrico M. Mendoza</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="councilor text-center">
                        <img src="{{ asset('images/councilor-mercado.jpg') }}" alt="Aldrin Gerrold C. Mercado" class="img-fluid rounded-circle border border-5 border-warning mb-4">
                        <p>Hon. Aldrin Gerrold C. Mercado</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="councilor text-center">
                        <img src="{{ asset('images/councilor-olivares-cuevas.jpg') }}" alt="Bernadeth V. Olivares-Cuevas" class="img-fluid rounded-circle border border-5 border-warning mb-4">
                        <p>Hon. Bernadeth V. Olivares-Cuevas</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="councilor text-center">
                        <img src="{{ asset('images/councilor-oliveros.jpg') }}" alt="Atty. Marky S. Oliveros" class="img-fluid rounded-circle border border-5 border-warning mb-4">
                        <p>Hon. Atty. Marky S. Oliveros</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="councilor text-center">
                        <img src="{{ asset('images/councilor-solidum.jpg') }}" alt="Vincent Jude T. Solidum" class="img-fluid rounded-circle border border-5 border-warning mb-4">
                        <p>Hon. Vincent Jude T. Solidum</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="councilor text-center">
                        <img src="{{ asset('images/councilor-vierneza.jpg') }}" alt="Iryne V. Vierneza" class="img-fluid rounded-circle border border-5 border-warning mb-4">
                        <p>Hon. Iryne V. Vierneza</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="councilor text-center">
                        <img src="{{ asset('images/councilor-villegas.jpg') }}" alt="Joie Chelsea V. Villegas" class="img-fluid rounded-circle border border-5 border-warning mb-4">
                        <p>Hon. Joie Chelsea V. Villegas</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="councilor text-center">
                        <img src="{{ asset('images/president-tayao.png') }}" alt="Diwa T. Tayao" class="img-fluid rounded-circle border border-5 border-warning mb-4">
                        <p>Hon. Diwa T. Tayao</p>
                        <p class="sp-position">ABC President</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="400">                    
                    <div class="councilor text-center">
                        <img src="{{ asset('images/president-castasus.png') }}" alt="Earl Gius Z. Castasus" class="img-fluid rounded-circle border border-5 border-warning mb-4">
                        <p>Hon. Earl Gius Z. Castasus</p>
                        <p class="sp-position">SK Federation President</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection