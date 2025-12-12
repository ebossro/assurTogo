<!DOCTYPE html>
<html>
<head>
    <title>Rendez-vous de validation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h2 style="color: #0d6efd;">Bonjour {{ $police->user->prenom }},</h2>
        <p>Votre demande de souscription (Police N° <strong>{{ $police->numeroPolice }}</strong>) a été pré-validée.</p>
        
        <p>Afin de finaliser votre dossier et activer votre couverture, nous vous invitons à vous présenter à notre agence pour un rendez-vous de validation.</p>
        
        <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #0d6efd; margin: 20px 0;">
            <p style="margin: 0;"><strong>📅 Date et Heure :</strong> {{ $police->date_rendez_vous->format('d/m/Y à H:i') }}</p>
            <p style="margin: 5px 0 0;"><strong>📍 Lieu :</strong> Agence Centrale AssurTogo, Lome</p>
        </div>

        <p><strong>Documents à apporter :</strong></p>
        <ul>
            <li>Pièce d'identité originale</li>
            <li>Photos d'identité (si non fournies)</li>
            <li>Justificatif de domicile</li>
        </ul>

        <p>Si vous ne pouvez pas honorer ce rendez-vous, merci de nous contacter au plus vite.</p>

        <p>Cordialement,<br>L'équipe AssurTogo</p>
    </div>
</body>
</html>
