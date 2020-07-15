<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../src/Entity/Destination.php';
require_once __DIR__ . '/../src/Entity/Quote.php';
require_once __DIR__ . '/../src/Entity/Site.php';
require_once __DIR__ . '/../src/Entity/Template.php';
require_once __DIR__ . '/../src/Entity/User.php';
require_once __DIR__ . '/../src/Helper/SingletonTrait.php';
require_once __DIR__ . '/../src/Context/ApplicationContext.php';
require_once __DIR__ . '/../src/Repository/Repository.php';
require_once __DIR__ . '/../src/Repository/DestinationRepository.php';
require_once __DIR__ . '/../src/Repository/QuoteRepository.php';
require_once __DIR__ . '/../src/Repository/SiteRepository.php';
require_once __DIR__ . '/../src/TemplateManager.php';

$faker = \Faker\Factory::create();

$template = new Template(
    1,
    'Votre livraison à [destination:countryName].',
    "
Bonjour [user:firstname],

Merci de nous avoir contactés pour votre livraison à [destination:countryName].

Bien cordialement,

L'équipe Convelio.com
");
$templateManager = new TemplateManager();
$quote = QuoteRepository::getInstance()->getById($faker->randomNumber());

if( isset($quote)) {
    $message = $templateManager->getTemplateComputed(
        $template,
        [
            'quote' => $quote,
            'destination' => DestinationRepository::getInstance()->getById($quote->getDestinationId()),
            'user' => ApplicationContext::getInstance()->getCurrentUser()
        ]
    );

    echo $message->subject . "\n" . $message->content;
}
else {
    echo "Element non trouvé";
}




