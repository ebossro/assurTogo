<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Attestation d'Assurance</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            border: 5px solid #0056b3; /* Bleu Assurance */
            padding: 10px;
            box-sizing: border-box;
            position: relative;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #0056b3;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 20px 0;
            background-color: #f0f8ff;
            padding: 10px;
            border-radius: 5px;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .row {
            margin-bottom: 15px;
            clear: both;
        }
        .label {
            font-weight: bold;
            width: 35%;
            float: left;
            color: #555;
        }
        .value {
            float: left;
            width: 65%;
            font-weight: bold;
        }
        .section-title {
            background-color: #0056b3;
            color: white;
            padding: 5px 10px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 15px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .stamp {
            margin-top: 30px;
            text-align: right;
            padding-right: 50px;
        }
        .stamp-box {
            border: 2px dashed #0056b3;
            display: inline-block;
            padding: 15px;
            color: #0056b3;
            font-weight: bold;
            transform: rotate(-5deg);
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">AssurTogo</div>
            <div class="subtitle">La protection santé pour tous, partout au Togo</div>
        </div>

        <div class="title text-center" style="text-align: center;">
            Attestation d'Assurance Santé
        </div>

        <div class="content">
            <p style="text-align: justify; margin-bottom: 20px;">
                Nous, <strong>AssurTogo</strong>, certifions par la présente que l'assuré(e) désigné(e) ci-dessous est couvert(e) par un contrat d'assurance santé conformément aux conditions générales de la police souscrite.
            </p>

            <div class="section-title">Informations sur l'Assuré</div>
            <div class="row clearfix">
                <div class="label">Nom et Prénoms :</div>
                <div class="value">{{ strtoupper($police->user->name) }} {{ $police->user->prenom }}</div>
            </div>
            <div class="row clearfix">
                <div class="label">Adresse :</div>
                <div class="value">{{ $police->user->adresse }}, {{ $police->user->quartier }}, {{ $police->user->ville }}</div>
            </div>
            <div class="row clearfix">
                <div class="label">Numéro de Téléphone :</div>
                <div class="value">{{ $police->user->telephone }}</div>
            </div>

            <div class="section-title">Détails de la Police</div>
            <div class="row clearfix">
                <div class="label">Numéro de Police :</div>
                <div class="value">{{ $police->numeroPolice }}</div>
            </div>
            <div class="row clearfix">
                <div class="label">Formule / Couverture :</div>
                <div class="value">{{ $police->formule ? $police->formule->nom : $police->typePolice }} ({{ $police->couverture }})</div>
            </div>
            <div class="row clearfix">
                <div class="label">Période de Validité :</div>
                <div class="value">Du {{ $police->dateDebut->format('d/m/Y') }} au {{ $police->dateFin->format('d/m/Y') }}</div>
            </div>
            <div class="row clearfix">
                <div class="label">Nombre de Bénéficiaires :</div>
                <div class="value">{{ $police->beneficiaires->count() }} personne(s)</div>
            </div>
            <div class="row clearfix">
                <div class="label">Statut :</div>
                <div class="value" style="color: green;">ACTIF</div>
            </div>

            <div class="section-title">Bénéficiaires Couverts</div>
            <table width="100%" cellspacing="0" cellpadding="5" style="font-size: 13px; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="border: 1px solid #ddd; text-align: left;">Nom & Prénoms</th>
                        <th style="border: 1px solid #ddd; text-align: left;">Relation</th>
                        <th style="border: 1px solid #ddd; text-align: left;">Date de Naissance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($police->beneficiaires as $beneficiaire)
                    <tr>
                        <td style="border: 1px solid #ddd;">{{ $beneficiaire->nomBeneficiaire }} {{ $beneficiaire->prenomBeneficiaire }}</td>
                        <td style="border: 1px solid #ddd;">{{ ucfirst($beneficiaire->relationBeneficiaire) }}</td>
                        <td style="border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($beneficiaire->dateNaissanceBeneficiaire)->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                    @if($police->beneficiaires->isEmpty())
                    <tr>
                        <td colspan="3" style="border: 1px solid #ddd; text-align: center; font-style: italic;">Aucun bénéficiaire déclaré</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <div class="stamp">
                <div class="stamp-box">
                    AssurTogo<br>
                    Validé le {{ now()->format('d/m/Y') }}<br>
                    Direction Générale
                </div>
            </div>

            <p style="font-size: 11px; margin-top: 30px; font-style: italic;">
                Cette attestation est délivrée pour servir et valoir ce que de droit. En cas d'urgence médicale, veuillez contacter notre centre d'assistance accessible 24h/24 et 7j/7.
            </p>
        </div>

        <div class="footer">
            AssurTogo S.A. - Siège Social : Lomé, Togo - Tél : +228 22 22 22 22 - Email : contact@assurtogo.tg
        </div>
    </div>
</body>
</html>
