<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 08.04.14
 * Time: 17:19
 */

namespace Core\View;

//TODO сделать чтобы Layout хранилсЯ в реестре и на момент рендеринга брать его оттуда

class Layout extends \Core\View\View {

    protected $content;



    /**
     * @param      $name
     * @param null $target
     * @return \Bluz\EventManager\EventManager
     */
    public function trigger($name, $target = null)
    {
       return \Core\Helper\Registry::getInstance() -> eventManager -> trigger('layout:' . $name, $target, array('layout' => $this));
       // return app()->getEventManager()->trigger('layout:' . $name, $target, array('layout' => $this));
    }

    /**
     * Set content
     *
     * @param mixed $content
     * @return View
     */
    public function setContent($content)
    {
        try {
            if (is_callable($content)) {
                $content = $content();
            }
            $this->content = $content;
        } catch (\Exception $e) {
            $this->content = $e->getMessage();
        }
        $this->content = $this->trigger('content', $this->content);
        return $this;
    }

    /**
     * Get content
     *
     * @return View
     */
    public function getContent()
    {
        return $this->content;
    }

} 