<?php
class Router
{
    const DEFAULT_REGEX = "@((?<!/)/[^/?#]+)|/@";
    private $_vars = array();
    private $path = false;

    public function parse($route) {

        if ($this->path) {
            $pos = strpos($route, $this->path);
            if ($pos === 0) {
                $route = str_replace($this->path, "", $route);
            }
        }

        if (!preg_match_all(self::DEFAULT_REGEX, $route, $matches)) {
            return $route;
        }

        $routeData = array();
        foreach ($matches[0] as $match) {
            $routeData[] = array(
                "type"  => ($this->isVariable($match) ? "var" : "cons"),
                "value" => $match
            );
        }

        return $routeData;
    }
    function setPath($path) {
        $this->path = $path;
    }
    function isVariable($match) {
        return preg_match('|^/{.*}|', $match);
    }

    function get($pattern, $function) {
        return $this->resolve($pattern, $function, "get");
    }

    function post($pattern, $function) {
        return $this->resolve($pattern, $function, "post");
    }

    function put($pattern, $function) {
        return $this->resolve($pattern, $function, "put");
    }

    function delete($pattern, $function) {
        return $this->resolve($pattern, $function, "delete");
    }

    function any($pattern, $function) {
        return $this->resolve($pattern, $function, "any");
    }

    function resolve($pattern, $function, $method) {
        if (!$this->isMethod($method))
            return false;
        $parsed_route = $this->parse($pattern);
        $URI = $_SERVER['REQUEST_URI'];

        if($this->checkRoute($parsed_route)) {
            return call_user_func_array($function, array_merge($this->_vars));
          }

    }
    function checkRoute($routeData) {
        $URI = $_SERVER['REQUEST_URI'];
        $URIParsed = $this->parse($URI);

        if (count($URIParsed) != count($routeData))
            return false;
        $vars = array();
        for ($i = 0; $i < count($URIParsed); $i++) {
            if ($routeData[$i]["type"] == "cons" && $routeData[$i]["value"] != $URIParsed[$i]["value"])
                return false;
            if ($routeData[$i]["type"] == "var") {
                $vars[] = substr($URIParsed[$i]["value"], 1);
            }
        }
        $this->_vars = $vars;
        return true;
    }

    function isMethod($method) {
        if ($method == "any") {
            return true;
        } else {
            return ((strtoupper($_SERVER["REQUEST_METHOD"]) == strtoupper($method)) || (strtoupper($_SERVER["REQUEST_METHOD"]) == "POST" && array_key_exists("_method", $_POST) && strtoupper($_POST["_method"]) == strtoupper($method)));
        }
    }

}
$route = new Router();
require 'routes.php';