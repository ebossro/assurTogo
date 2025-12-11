<x-mail::message>
# Bonjour {{ $police->user->prenom }},

Nous avons une excellente nouvelle ! 🎉

Votre demande de souscription pour l'assurance **{{ $police->typePolice }}** a été validée avec succès par notre équipe. Il ne vous reste plus qu'une étape pour être protégé.

<x-mail::panel>
### Récapitulatif de votre police
**Numéro de police :** {{ $police->numeroPolice }}  
**Couverture :** {{ $police->couverture }}  
**Période :** {{ $police->dateDebut->format('d/m/Y') }} au {{ $police->dateFin->format('d/m/Y') }}  
  
**Montant à régler :** {{ number_format($police->primeMensuelle, 0, ',', ' ') }} FCFA
</x-mail::panel>

Pour activer immédiatement votre couverture, veuillez effectuer le paiement de votre première cotisation en cliquant sur le bouton ci-dessous.

<x-mail::button :url="$url" color="success">
Payer et activer ma police
</x-mail::button>

Ce lien de paiement est sécurisé. Si vous avez des questions, notre support est à votre disposition.

Cordialement,<br>
**L'équipe AssurTogo**
</x-mail::message>
