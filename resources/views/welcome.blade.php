@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="position-relative pt-5 pb-5 overflow-hidden" style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%);">
    <div class="container text-center pt-4 pb-5 position-relative z-1">
        
        <!-- Badge -->
        <div class="d-inline-flex align-items-center bg-white border border-primary border-opacity-25 text-primary rounded-pill px-3 py-2 mb-4 shadow-sm">
            <i class="bi bi-shield-check me-2"></i>
            <span class="fw-semibold small">Plateforme digitale d'assurance santé</span>
        </div>

        <!-- Headline -->
        <h1 class="display-4 fw-bolder mb-4 text-dark lh-1">
            Gérez votre assurance <br>
            santé <span class="text-primary">sans vous déplacer</span>
        </h1>

        <!-- Subheadline -->
        <p class="lead text-muted mb-5 mx-auto" style="max-width: 650px;">
            AssurTogo simplifie la souscription, le suivi et le renouvellement de votre couverture santé au Togo et en Afrique. Tout en ligne, en quelques clics.
        </p>

        <!-- Buttons -->
        <div class="d-flex justify-content-center gap-3 mb-5">
            @auth
            @if (auth()->user()->police && auth()->user()->police->statut === 'en_attente')
                <a href="{{ route('polices.confirmation') }}" class="btn btn-primary btn-lg px-4 py-3 rounded-3 shadow-lg border-0">
                    Souscrire maintenant
                </a>
            @else
                <a href="{{ route('polices.create') }}" class="btn btn-primary btn-lg px-4 py-3 rounded-3 shadow-lg border-0">
                    Souscrire maintenant
                </a>
            @endif
            @else
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 py-3 rounded-3 shadow-lg border-0">
                    Commencer gratuitement
                </a>
            @endauth
            <a href="#fonctionnalites" class="btn btn-outline-secondary btn-lg px-4 py-3 rounded-3 shadow-sm">
                Découvrir les fonctionnalités
            </a>
        </div>

        <!-- Pillars Cards -->
        <div class="row g-4 justify-content-center mt-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-2 rounded-4">
                    <div class="card-body d-flex align-items-center text-start">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">
                            <i class="bi bi-phone fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">100% en ligne</h6>
                            <p class="text-muted small mb-0">Souscrivez depuis votre téléphone.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-2 rounded-4">
                    <div class="card-body d-flex align-items-center text-start">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">
                            <i class="bi bi-arrow-repeat fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Renouvellement auto</h6>
                            <p class="text-muted small mb-0">Processus automatisés et simples.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-2 rounded-4">
                    <div class="card-body d-flex align-items-center text-start">
                        <div class="flex-shrink-0 bg-info bg-opacity-10 text-info rounded-3 p-3 me-3">
                            <i class="bi bi-shield-lock fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Sécurisé et fiable</h6>
                            <p class="text-muted small mb-0">Vos données sont protégées.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Decorative blobs -->
    <div class="position-absolute top-0 start-0 translate-middle rounded-circle bg-primary opacity-10 blur-3xl" style="width: 400px; height: 400px; filter: blur(80px); z-index: 0;"></div>
    <div class="position-absolute top-50 end-0 translate-middle-y rounded-circle bg-info opacity-10 blur-3xl" style="width: 300px; height: 300px; filter: blur(60px); z-index: 0;"></div>
</section>

<!-- Features Section -->
<section id="fonctionnalites" class="py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5 mx-auto" style="max-width: 700px;">
            <h2 class="fw-bold mb-3">Tout ce dont vous avez besoin</h2>
            <p class="text-muted">Une plateforme complète pour simplifier chaque étape de votre parcours d'assurance santé.</p>
        </div>

        <div class="row g-4">
            <!-- Feature 1 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift transition">
                    <div class="card-body p-4">
                        <div class="d-inline-block bg-primary bg-opacity-10 text-light rounded-3 p-3 mb-3">
                            <i class="bi bi-file-earmark-text fs-4"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Souscription simplifiée</h5>
                        <p class="text-muted small">Remplissez votre demande en ligne en quelques minutes. Obtenez votre contrat instantanément.</p>
                    </div>
                </div>
            </div>
            <!-- Feature 2 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift transition">
                    <div class="card-body p-4">
                        <div class="d-inline-block bg-primary bg-opacity-10 text-light rounded-3 p-3 mb-3">
                            <i class="bi bi-qr-code fs-4"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Carte virtuelle et QR</h5>
                        <p class="text-muted small">Recevez votre carte d'assurance numérique avec un QR code unique pour vos consultations.</p>
                    </div>
                </div>
            </div>
            <!-- Feature 3 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift transition">
                    <div class="card-body p-4">
                        <div class="d-inline-block bg-primary bg-opacity-10 text-light rounded-3 p-3 mb-3">
                            <i class="bi bi-bell fs-4"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Notifications intelligentes</h5>
                        <p class="text-muted small">Soyez alerté des rappels de paiement, de l'état de vos sinistres et de vos remboursements.</p>
                    </div>
                </div>
            </div>
            <!-- Feature 4 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift transition">
                    <div class="card-body p-4">
                        <div class="d-inline-block bg-primary bg-opacity-10 text-light rounded-3 p-3 mb-3">
                            <i class="bi bi-arrow-clockwise fs-4"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Renouvellement facile</h5>
                        <p class="text-muted small">Renouvelez votre contrat en un clic, sans paperasse supplémentaire selon vos préférences.</p>
                    </div>
                </div>
            </div>
            <!-- Feature 5 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift transition">
                    <div class="card-body p-4">
                        <div class="d-inline-block bg-primary bg-opacity-10 text-light rounded-3 p-3 mb-3">
                            <i class="bi bi-speedometer2 fs-4"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Tableau de bord personnel</h5>
                        <p class="text-muted small">Suivez vos consommations, votre historique de paiement et vos remboursements.</p>
                    </div>
                </div>
            </div>
            <!-- Feature 6 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift transition">
                    <div class="card-body p-4">
                        <div class="d-inline-block bg-primary bg-opacity-10 text-light rounded-3 p-3 mb-3">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Par une compagnie</h5>
                        <p class="text-muted small">AssurTogo est un produit réglé par des clients valides, en assurance et avec des statistiques.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Comparison Section -->
<section id="avantages" class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Pourquoi choisir AssurConnect ?</h2>
            <p class="text-muted">Nous transformons les défis de l'assurance santé en solutions simples.</p>
        </div>

        <div class="row g-4">
            <!-- Problems -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 h-100 border border-danger border-opacity-10" style="background-color: #fef2f2;">
                    <h5 class="text-danger fw-bold mb-4">Les problèmes actuels</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center text-dark opacity-75">
                            <span class="bg-danger bg-opacity-10 text-danger rounded-circle p-1 me-3 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;"><i class="bi bi-x small"></i></span>
                            Longues files d'attente dans les agences
                        </li>
                        <li class="mb-3 d-flex align-items-center text-dark opacity-75">
                            <span class="bg-danger bg-opacity-10 text-danger rounded-circle p-1 me-3 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;"><i class="bi bi-x small"></i></span>
                            Dossiers égarés ou perdus
                        </li>
                        <li class="mb-3 d-flex align-items-center text-dark opacity-75">
                            <span class="bg-danger bg-opacity-10 text-danger rounded-circle p-1 me-3 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;"><i class="bi bi-x small"></i></span>
                            Délais de remboursement lents
                        </li>
                        <li class="mb-3 d-flex align-items-center text-dark opacity-75">
                            <span class="bg-danger bg-opacity-10 text-danger rounded-circle p-1 me-3 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;"><i class="bi bi-x small"></i></span>
                            Manque d'information fiable
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Solutions -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 h-100 border border-primary border-opacity-10" style="background-color: #f0f9ff;">
                    <h5 class="text-primary fw-bold mb-4">Notre solution</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center text-dark opacity-75">
                            <span class="bg-primary bg-opacity-10 text-light rounded-circle p-1 me-3 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;"><i class="bi bi-check small"></i></span>
                            Souscription 100% digitale depuis chez vous
                        </li>
                        <li class="mb-3 d-flex align-items-center text-dark opacity-75">
                            <span class="bg-primary bg-opacity-10 text-light rounded-circle p-1 me-3 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;"><i class="bi bi-check small"></i></span>
                            Préservation des données cryptées
                        </li>
                        <li class="mb-3 d-flex align-items-center text-dark opacity-75">
                            <span class="bg-primary bg-opacity-10 text-light rounded-circle p-1 me-3 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;"><i class="bi bi-check small"></i></span>
                            Rapidité des remboursements
                        </li>
                        <li class="mb-3 d-flex align-items-center text-dark opacity-75">
                            <span class="bg-primary bg-opacity-10 text-light rounded-circle p-1 me-3 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;"><i class="bi bi-check small"></i></span>
                            Transparence totale
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Contactez-nous</h2>
            <p class="text-muted">Une question ? Besoin d'aide ? Notre équipe est là pour vous accompagner.</p>
        </div>

        <div class="row g-5">
            <!-- Form -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Envoyez-nous un message</h4>
                        <form>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Nom complet</label>
                                <input type="text" class="form-control bg-light border-0 py-2" placeholder="Votre nom">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Email</label>
                                <input type="email" class="form-control bg-light border-0 py-2" placeholder="votre@email.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Téléphone</label>
                                <input type="tel" class="form-control bg-light border-0 py-2" placeholder="+228 ...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Message</label>
                                <textarea class="form-control bg-light border-0 py-2" rows="4" placeholder="Comment pouvons-nous vous aider ?"></textarea>
                            </div>
                            <button type="button" class="btn btn-primary w-100 py-2 fw-bold">Envoyer le message</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="col-lg-6">
                <div class="d-flex flex-column gap-4">
                    <div class="bg-primary bg-opacity-10 p-4 rounded-4">
                        <h4 class="fw-bold mb-4">Nos coordonnées</h4>
                        <div class="d-flex mb-3">
                            <div class="bg-white text-primary rounded-circle p-2 me-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Email</h6>
                                <p class="text-muted mb-0">contact@assurconnect.com</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="bg-white text-primary rounded-circle p-2 me-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Tél</h6>
                                <p class="text-muted mb-0">+228 70 00 00 00</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="bg-white text-primary rounded-circle p-2 me-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Adresse</h6>
                                <p class="text-muted mb-0">Rue de la Paix, Lomé, Togo</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-4 shadow-sm">
                        <h5 class="fw-bold mb-2">Horaires d'ouverture</h5>
                        <p class="text-muted small mb-1">Lundi - Vendredi: 8h00 - 18h00</p>
                        <p class="text-muted small mb-0">Samedi: 9h00 - 13h00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container py-4">
        <h2 class="fw-bold mb-3">Prêt à simplifier votre assurance santé ?</h2>
        <p class="lead mb-5 text-white-50">Rejoignez des milliers d'assurés qui ont déjà choisi la simplicité et la transparence.</p>
        <div class="d-flex justify-content-center gap-3">
            @auth
                @if(Auth::user()->police)
                    <a href="{{ route('dashboard.index') }}" class="btn btn-success btn-lg px-4 fw-bold shadow">
                        Aller au tableau de bord <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                @else
                    <a href="{{ route('polices.create') }}" class="btn btn-primary btn-lg px-4 fw-bold shadow">
                        Créer mon compte GRATUITEMENT
                    </a>
                @endif
            @else
                <a href="{{ route('register') }}" class="btn btn-success btn-lg px-4 fw-bold shadow">
                    Créer mon compte GRATUITEMENT <i class="bi bi-arrow-right ms-2"></i>
                </a>
            @endauth
            <a href="#contact" class="btn btn-outline-light btn-lg px-4">
                Contacter notre équipe
            </a>
        </div>
    </div>
</section>
    <!-- Chatbot Trigger Button -->
    <div class="position-fixed bottom-0 end-0 m-4 z-3">
        <button class="btn btn-lg rounded-pill shadow-lg d-flex align-items-center gap-2 text-white hover-scale" 
                style="background-color: #1c03ff; border: none; padding: 12px 24px; transition: transform 0.2s;">
            <i class="bi bi-stars"></i>
            <span class="fw-bold">Emmanuel IA</span>
        </button>
    </div>
@endsection

@section('footer')
    <footer class="footer">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-4">
                    <h5 class="mb-3">Assur<span class="text-primary">Togo</span></h5>
                    <p class="text-muted">Simplifiez la gestion de votre assurance santé au Togo et en Afrique. Tout en ligne, sécurisé et rapide.</p>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="mb-3">Produit</h6>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Fonctionnalités</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Avantages</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Tarifs</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="mb-3">Support</h6>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Centre d'aide</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Contact</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Confidentialité</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <p class="text-muted small mb-0">&copy; {{ date('Y') }} AssurTogo. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>
@endsection
