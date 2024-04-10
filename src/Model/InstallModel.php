<?php

namespace App\Model;

class InstallModel
{
    const SITE_TITLE_LABEL = 'Titre du blog';
    const SITE_TITLE_NAME = 'blog_title';

    const SITE_INSTALLED_LABEL = 'Site installé';
    const SITE_INSTALLED_NAME = 'blog_installed';

    private ?string $siteTitle;

    private ?string $username;

    private ?string $password;

    /**
     * Get the value of siteTitle
     */ 
    public function getSiteTitle()
    {
        return $this->siteTitle;
    }

    /**
     * Set the value of siteTitle
     *
     * @return  self
     */ 
    public function setSiteTitle($siteTitle)
    {
        $this->siteTitle = $siteTitle;

        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}