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
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    /**
     * @param $text
     * @param array $data
     * @return string $text
     *
     * Replace all placeholders with true values in text
     */
    private function computeText($text, array $data)
    {
        // If a valid and existing quote is passed
        if ( isset($data['quote']) and $data['quote'] instanceof Quote )
        {
            $quote = $data['quote'];
            $usefulObject = SiteRepository::getInstance()->getById($quote->siteId);
            $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->destinationId);

            // Handle quote summary placeholders
            if (strpos($text, '[quote:summary_html]') !== false) {
                $text = str_replace(
                    '[quote:summary_html]',
                    Quote::renderHtml($quote),
                    $text
                );
            }
            if (strpos($text, '[quote:summary]') !== false) {
                $text = str_replace(
                    '[quote:summary]',
                    Quote::renderText($quote),
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
        $_user  = (isset($data['user'])  and ($data['user']  instanceof User)) ? $data['user']  : $this->APPLICATION_CONTEXT->getCurrentUser();
        if($_user) {
            (strpos($text, '[user:first_name]') !== false) and $text = str_replace('[user:first_name]'       , ucfirst(mb_strtolower($_user->firstname)), $text);
        }

        return $text;
    }
}




/**
 * $variables = array("first_name"=>"John","last_name"=>"Smith","status"=>"won");
$string = 'Dear {FIRST_NAME} {LAST_NAME}, we wanted to tell you that you {STATUS} the competition.';

foreach($variables as $key => $value){
$string = str_replace('{'.strtoupper($key).'}', $value, $string);
}

echo $string;
 */

