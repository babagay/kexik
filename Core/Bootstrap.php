<?php

namespace Application;

use Bluz\Application\Application;
use Bluz\Application\Exception\ForbiddenException;
use Bluz\EventManager\Event;

class Bootstrap extends Application
{
   /**
     * initial environment
     *
     * @param string $environment
     * @return self
     */
    public function init($environment = 'production')
    {
        $res = parent::init($environment);

///$this->debugFlag = true;

        // Profiler hooks
        if ($this->debugFlag) {
            $this->getEventManager()->attach(
                'layout:header',
                function ($event) {
                    /* @var \Bluz\View\Layout $layout */
                    /* @var \Bluz\EventManager\Event $event */
                    $layout = $event->getParam('layout');

                    /* @var \Bluz\EventManager\Event $event */
                    // $this->log('layout:header');
                    Application::logStatic('layout:header');
                }
            );
            $this->getEventManager()->attach(
                'layout:content',
                function ($event) {
                    /* @var \Bluz\EventManager\Event $event */
                    //$this->log('layout:content');
                    Application::logStatic('layout:content');
                }
            );
            $this->getEventManager()->attach(
                'layout:footer',
                function ($event) {
                    /* @var \Bluz\EventManager\Event $event */
                    //$this->log('layout:footer');
                    Application::logStatic('layout:footer');
                }
            );
        }

        // dispatch hook for acl realization
        $this->getEventManager()->attach(
            'dispatch',
            function ($event) {
                /// @var \Bluz\EventManager\Event $event
                $eventParams = $event->getParams();
                Application::logStatic('bootstrap:dispatch: '.$eventParams['module'].'/'.$eventParams['controller']);
            }
        );


        // widget hook for acl realization
        $this->getEventManager()->attach(
            'widget',
            function ($event) {
                /// @var \Bluz\EventManager\Event $event
                $eventParams = $event->getParams();
                //$this->log('bootstrap:widget: '.$eventParams['module'].'/'.$eventParams['widget']);
                Application::logStatic('bootstrap:widget: '.$eventParams['module'].'/'.$eventParams['widget']);
            }
        );

        // example of setup Layout
        $layout = $this->getLayout();

        $layout->title("Кексик");

        return $res;
    }

    /**
     * denied
     *
     * @throws ForbiddenException
     * @return void
     */
    public function denied()
    {
        // process AJAX request
        if (!$this->getRequest()->isXmlHttpRequest()) {
            $this->getMessages()->addError('You don\'t have permissions, please sign in');
        }

        // redirect to login page
        if (!$this->getAuth()->getIdentity()) {
            // save URL to session
            $this->getSession()->rollback = $this->getRequest()->getRequestUri();
            $this->redirectTo('users', 'signin');
        }
        throw new ForbiddenException();
    }

    /**
     * render
     *
     * @return void
     */
    public function render()
    {
        if ($this->debugFlag && !headers_sent()) {
            $debug = sprintf(
                "%f; %skb",
                microtime(true) - $_SERVER['REQUEST_TIME'],
                ceil((memory_get_usage()/1024))
            );
            header(
                'Bluz-Debug: '. $debug .'; '.
                $this->getRequest()->getModule() .'/'. $this->getRequest()->getController()
            );
            $bar = json_encode($this->getLogger()->get('info'));
            header('Bluz-Bar: '. $bar);

            //fb( "Debug info. [module]: " . $this->getRequest()->getModule() .' [controller]: '. $this->getRequest()->getController() );
        }
        parent::render();
    }

    /**
     * @return Application
     */
    public function finish()
    {
        if ($messages = $this->getLogger()->get('error')) {
            errorLog(join("\n", $messages)."\n");
        }
        return $this;
    }
}
