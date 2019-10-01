<?php


namespace PlusForta\PostmarkBundle\Mail;


/**
 * @property mixed|string salutation
 * @property mixed|string lastName
 * @property mixed|string hotlineNumber
 * @property mixed|string siteLink
 * @property mixed|string companyAddress
 * @property mixed|string companyName
 * @property mixed|string productUrl
 * @property mixed|string product
 * @property mixed|string siteMail
 * @property mixed|string hostSiteUrl
 */
class KFKundenanfrageTemplateMail extends BaseTemplateMail
{
    protected const SERVER_ID = 'plusforta.de';

    protected const TEMPLATE_ID =   12645253;

    protected static $parameters = [
        'salutation',
        'lastName',
        'hotlineNumber',
        'siteLink',
        'hostSiteUrl',
        'siteMail',
        'product',
        'productUrl',
        'companyName',
        'companyAddress',
    ];

    public function getTemplate(): array
    {
        return [
            'anrede' => $this->salutation,
            'nachname' => $this->lastName,
            'hotlineNum' => $this->hotlineNumber,
            'sitelink' => $this->siteLink,
            'hostSiteUrl' => $this->hostSiteUrl,
            'siteMail' => $this->siteMail,
            'product_name' => $this->product,
            'product_url' => $this->productUrl,
            'company_name' => $this->companyName,
            'company_address' => $this->companyAddress,
        ];
    }

}