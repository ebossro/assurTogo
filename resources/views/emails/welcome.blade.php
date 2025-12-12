<!DOCTYPE html>
<html>
<head>
    <title>Bienvenue chez AssurTogo</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h2 style="color: #0d6efd;">Félicitations {{ $police->user->prenom }} !</h2>
        <p>Votre souscription (Police N° <strong>{{ $police->numeroPolice }}</strong>) a été validée avec succès suite à votre rendez-vous en agence.</p>
        
        <p>Votre compte est désormais <strong>ACTIF</strong>. Vous pouvez accéder à votre espace personnel pour suivre vos remboursements, déclarer un sinistre ou télécharger vos documents.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('dashboard.index') }}" style="background-color: #0d6efd; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Accéder à mon Dashboard</a>
        </div>

        <p>Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>
        <a href="{{ route('dashboard.index') }}">{{ route('dashboard.index') }}</a></p>

        <p>Merci de votre confiance !</p>

        <p>Cordialement,<br>L'équipe AssurTogo</p>
    </div>
</body>
</html>
