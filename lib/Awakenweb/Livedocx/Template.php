<?php

/*
 * The MIT License
 *
 * Copyright 2014 Mathieu SAVELLI <mathieu.savelli@awakenweb.fr>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Awakenweb\Livedocx;

use Awakenweb\Livedocx\Exceptions\SoapException;
use Awakenweb\Livedocx\Exceptions\Templates\IgnoreException;
use Awakenweb\Livedocx\Exceptions\Templates\InvalidException;
use Awakenweb\Livedocx\Exceptions\Templates\StatusException;

/**
 * Description of Template
 *
 * @author Mathieu SAVELLI <mathieu.savelli@awakenweb.fr>
 */
abstract class Template
{

    use Soap\HasSoapClient;

    /**
     *
     * @var string
     */
    protected $templateName;

    /**
     *
     * @var boolean
     */
    protected $isActive = false;

    /**
     * Set the filename of the template
     *
     * @param string $template_name
     */
    public function setName($template_name)
    {
        $this->templateName = $template_name;
    }

    /**
     * Return the list of all templates uploaded to the Livedocx service
     *
     * @return type
     *
     * @throws StatusException
     */
    public function listAll()
    {
        try {
            $ret    = array();
            $result = $this->getSoapClient()->ListTemplates();
            if ( isset($result->ListTemplatesResult) ) {
                $ret = $this->getSoapClient()->backendListArrayToMultiAssocArray($result->ListTemplatesResult);
            }

            return $ret;
        } catch ( SoapException $ex ) {
            throw new StatusException('Error while getting the list of all uploaded templates' , $ex);
        }
    }

    /**
     * Return a list of all the accepted template formats you can use to generate your document
     *
     * @return array
     *
     * @throws StatusException
     */
    public function getAcceptedTemplateFormats()
    {
        try {
            $ret    = array();
            $result = $this->getSoapClient()->GetTemplateFormats();
            if ( isset($result->GetTemplateFormatsResult->string) ) {
                $ret = $result->GetTemplateFormatsResult->string;
                $ret = array_map('strtolower' , $ret);
            }

            return $ret;
        } catch ( SoapException $ex ) {
            throw new StatusException('Error while getting the list of accepted template formats' , $ex);
        }
    }

    /**
     * Get the list of all available fonts on the Livedocx service
     *
     * @return type
     *
     * @throws StatusException
     */
    public function getAvailableFonts()
    {
        try {
            $ret    = array();
            $result = $this->getSoapClient()->GetFontNames();
            if ( isset($result->GetFontNamesResult->string) ) {
                $ret = $result->GetFontNamesResult->string;
            }

            return $ret;
        } catch ( SoapException $ex ) {
            throw new StatusException('Error while getting the list of available fonts' , $ex);
        }
    }

    /**
     * Tell the Livedocx service to ignore included subtemplates when generating the final
     * document
     *
     * @param boolean $state
     *
     * @return Template
     *
     * @throws IgnoreException
     */
    public function ignoreAllSubTemplates($state = true)
    {
        $state = ( bool ) $state;
        try {
            $this->getSoapClient()->SetIgnoreSubTemplates(array(
                'ignoreSubTemplates' => $state ,
            ));

            return $this;
        } catch ( SoapException $ex ) {
            throw new IgnoreException("Error while telling the server to ignore subtemplates" , $ex);
        }
    }

    /**
     * Define a list of subtemplates to ignore when generating the final document
     *
     * @param array $subtemplates_list
     *
     * @return Template
     *
     * @throws IgnoreException
     * @throws InvalidException
     */
    public function ignoreListOfSubTemplates($subtemplates_list)
    {
        if ( ! is_array($subtemplates_list) ) {
            throw new InvalidException('List of subtemplate filenames must be an array');
        }
        $filenames = array_values($subtemplates_list);
        try {
            $this->getSoapClient()->SetSubTemplateIgnoreList(array(
                'filenames' => $filenames ,
            ));

            return $this;
        } catch ( SoapException $ex ) {
            throw new IgnoreException("Error while telling the server to ignore a list of subtemplates" , $ex);
        }
    }

    abstract public function getName();

    abstract public function setAsActive();
}
