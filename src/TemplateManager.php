<?php

class TemplateManager
{
    private  $APPLICATION_CONTEXT;

    public function __construct()
    {
        $this->APPLICATION_CONTEXT = ApplicationContext::getInstance();
    }

    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced->subject = $this->computeTextNew($replaced->subject, $data);
        $replaced->content = $this->computeTextNew($replaced->content, $data);

        return $replaced;
    }

    /**
     * @param $text
     * @param array $data
     * @return string $text
     *
     * Replace all placeholders with true values in text for quote
     */
    private function computeText($text, array $data)
    {
        // If a valid and existing quote is passed
        if ( isset($data['quote']) and $data['quote'] instanceof Quote )
        {
            /**
             * @var Quote $quote
             */
            $quote = $data['quote'];
            $usefulObject = SiteRepository::getInstance()->getById($quote->siteId);
            $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->destinationId);

            // Handle quote summary placeholders
            if (strpos($text, '[quote:summary_html]') !== false) {
                $text = str_replace(
                    '[quote:summary_html]',
                    $quote->renderHtml(),
                    $text
                );
            }
            if (strpos($text, '[quote:summary]') !== false) {
                $text = str_replace(
                    '[quote:summary]',
                    $quote->renderText(),
                    $text
                );
            }


            // Handle destination properties
            if(strpos($text, '[quote:destination_link]') !== false && isset($destinationOfQuote)){
                $text = str_replace('[quote:destination_link]', $usefulObject->url . '/' . $destinationOfQuote->countryName . '/quote/' . $quote->id, $text);

            } else
                $text = str_replace('[quote:destination_link]', '', $text);

            (strpos($text, '[quote:destination_name]') !== false) and $text = str_replace('[quote:destination_name]',$destinationOfQuote->countryName, $text);

        }

        /*
         * USER
         * [user:*]
         */
        $_user = (isset($data['user'])  and ($data['user']  instanceof User)) ? $data['user']  : $this->APPLICATION_CONTEXT->getCurrentUser();

        (strpos($text, '[user:first_name]') !== false) and $text = str_replace('[user:first_name]', ucfirst(mb_strtolower($_user->firstname)), $text);

        return $text;
    }


    /**
     * @param $text
     * @param array $data
     * @return string $text
     *
     * Replace all placeholders with true values in text for quote
     */
    private function computeTextNew($text, array $data) {

        // placeholders are [objectName:propertyToDisplay]
        $text = preg_replace_callback('/\[([a-zA-Z]+)\:([a-zA-Z]+)\]/',
            function ($matches) use ($data) {
                if( array_key_exists( $matches[1], $data) ) {
                    return $this->getPropertyIfExists($data[$matches[1]], $matches[2]);
                } else {
                    return "";
                }
            }, $text);

        return $text;
    }

    /**
     * @param ObjectDisplay $objectDisplay
     * @param $property
     * @return mixed
     *
     * Call method or property from object according to sntax [object:properyName] in template
     */
    private function getPropertyIfExists( ObjectDisplay $objectDisplay, $property ){
        try {
            if ( is_callable( [$objectDisplay, $property]) ) {
                return $objectDisplay->{$property};
            }
            elseif ( is_callable( [ $objectDisplay, "get" . ucfirst($property) ] ) ) {

                $methodCalled = "get" . ucfirst($property);
                return $objectDisplay->{$methodCalled}();
            }
        }  catch (Exception $e) {
            echo "Méthode $property : does not exist \n";
        }
    }
}
