<?php
namespace UrlManager\Models;

class Password
{
    protected $password;
    protected $salt = 'xsolla';



    public function __construct($password, $hash = true)
    {
        if ($hash)
        {
            $this->password = hash('md5',(hash('md5',$password).$this->salt));
        } else {
            $this->password = $password;
        }
    }



    public function getPassword()
    {
        return $this->password;
    }
}
