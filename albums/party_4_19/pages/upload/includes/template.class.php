<?php
/************************************************\
 * Template class
 * **********************************************
 * File Name	: template.class.php
 * Author       : Tuan Do @ www.celerondude.com
 * Email		: dork@celerondude.com
 * Credit:      : copied Smarty tags from Smarty
                template engine @ http://smarty.php.net
\************************************************/

class Template
{
    var $_vars = array();
    var $_template_dir = '';
    var $_compiled = array();
    var $_cache_dir = './';
    var $_cache = 0;

    function Template ($template_dir = '')
    {
        $this->_template_dir = rtrim ($template_dir, '/') . '/';

        $this->_vars['_GET'] =& $_GET;
        $this->_vars['_POST'] =& $_POST;
        $this->_vars['_REQUEST'] =& $_REQUEST;
        $this->_vars['_COOKIE'] =& $_COOKIE;
        $this->_vars['_SERVER'] =& $_SERVER;

        // basic template variables
        $this->_vars['_VARS']['today'] = date('l');
        $this->_vars['_VARS']['time'] = date('h:i A');

    }
    
    function display ($template_name)
    {
        if( empty($template_name) )
        {
            exit('No template assigned.');
        }
        print $this->fetch($template_name);
    }
    
    function &fetch($template_name)
    {
        $template_id = md5 ($template_name);
        
        if(!isset($this->_compiled[$template_id]))
        {
            $f = $this->_template_dir . $template_name;
            $f_cached = $this->_cache_dir . $template_id . '.php';
            
            if(file_exists($f_cached))
            {
                $_vars =& $this->_vars;
                ob_start();
                include($f_cached);
                $r = ob_get_contents();
                ob_end_clean();
                return $r;
            }
            elseif($this->_cache)
            {
                $fp = @fopen($f_cached, 'w');
                if(!$fp)
                {
                    exit('template->fetch(): unable to write to cache directory');
                }
                $this->_compiled[$template_id] =& $this->_compile(file($f));
                fwrite($fp, '<?php ' . $this->_compiled[$template_id] . ' ?' . '>');
                fclose($fp);
                $_vars =& $this->_vars;
                ob_start();
                include($f_cached);
                $r = ob_get_contents();
                ob_end_clean();
                return $r;
            }
            else
            {
                $this->_compiled[$template_id] =& $this->_compile(file($f));
                $_vars =& $this->_vars;
                ob_start();
                eval ($this->_compiled[$template_id]);
                $r = ob_get_contents();
                ob_end_clean();
                return $r;
            }
        }

    }
    
    function assign($varname, $vardata)
    {
        $this->_vars[$varname] = $vardata;
    }

    function assign_by_ref($varname, &$vardata)
    {
        $this->_vars[$varname] = &$vardata;
    }
    
    function assign_append($varname, $append)
    {
        if(!isset($this->_vars[$varname]))
        {
            $this->_vars[$varname] = $append;
        }
        else
        {
            $this->_vars[$varname] .= $append;
        }
    }
    
    
    function &_compile ($codes)
    {
        for($i = 0; $i < count ($codes); $i++)
        {
            $codes[$i] = str_replace("\\", "\\\\", $codes[$i]);
            $codes[$i] = str_replace("'", "\'", $codes[$i]);
            
            // regular varibles
            if (preg_match_all ("#\{\\$([a-zA-Z0-9_.\[\]]+?)\}#i", $codes[$i], $m))
            {
                for ($a = 0; $a < count($m[0]); $a++)
                {
                    $codes[$i] = str_replace($m[0][$a], "'; print " . $this->_ref($m[1][$a]) . " . '", $codes[$i]);
                }
            }
            // variables with default values
            if (preg_match_all("#\{\\$([a-zA-Z0-9_.\[\]]+?)\|\"(.*?)\"\}#i", $codes[$i], $m))
            {
                for($a = 0; $a < count($m[0]); $a++)
                {
                    $codes[$i] = str_replace($m[0][$a], "' . (isset(" . $this->_ref($m[1][$a], 0) . ") ? " . $this->_ref($m[1][$a]) . " : '" . $m[2][$a] . "') . '" , $codes[$i]);
                }
            }
            // variables with modifers
            if(preg_match_all("#\{\\$([a-zA-Z0-9_.\[\]]+?)\|(.+?)\}#i", $codes[$i], $m))
            {

                for($a = 0; $a < count($m[0]); $a++)
                {
                    $codes[$i] = str_replace($m[0][$a], "' . (isset(" . $this->_ref($m[1][$a], 0) . ") ? \$this->" . $m[2][$a] . "(" . $this->_ref($m[1][$a]) . ")" . " : '" . $m[2][$a] . "') . '" , $codes[$i]);
                }
            }
            // if with value
            if(preg_match_all("#\{if(.+?)\}#i", $codes[$i], $m))
            {
                for($a = 0; $a < count($m[0]); $a++)
                {
                    $inside = stripslashes($m[1][$a]);
                    $inside = preg_replace("#\\$([a-zA-Z0-9_.\[\]]+)#ie", "\$this->_ref('$1')", $inside);
                    $codes[$i] = str_replace($m[0][$a], "'; if(" . $inside . ") { print '", $codes[$i]);
                }
            }
            // if with no value, true/false
            if(preg_match_all("#\{if ([\!]*)\\$([a-zA-Z0-9_.\[\]]+?)\}#i", $codes[$i], $m))
            {
                for($a = 0; $a < count($m[0]); $a++)
                {
                    $codes[$i] = str_replace($m[0][$a], "';if(" . $m[1][$a] . $this->_ref($m[2][$a]) . "){print'", $codes[$i]);
                    $codes[$i] = rtrim($codes[$i]);
                }
            }
            // elseif with value
            if(preg_match_all("#\{elseif(.+?)\}#i", $codes[$i], $m))
            {
                for($a = 0; $a < count($m[0]); $a++)
                {
                    $inside = stripslashes($m[1][$a]);
                    $inside = preg_replace("#\\$([a-zA-Z0-9_.\[\]]+)#ie", "\$this->_ref('$1')", $inside);
                    $codes[$i] = str_replace($m[0][$a], "';} elseif(" . $inside . ") { print '", $codes[$i]);
                }
            }
            // elseif with no value
            if(preg_match_all("#\{elseif ([\!]*)\\$([a-zA-Z0-9_.\[\]]+?)\}#i", $codes[$i], $m))
            {
                for($a = 0; $a < count($m[0]); $a++)
                {
                    $codes[$i] = str_replace($m[0][$a], "';}elseif(" . $m[1][$a] . $this->_ref($m[2][$a]) . "){print'", $codes[$i]);
                    $codes[$i] = rtrim($codes[$i]);
                }
            }
            // else
            $codes[$i] = str_replace('{else}', "';}else{print'", $codes[$i]);
            // end if
            $codes[$i] = str_replace('{/if}', "';}print'", $codes[$i]);
            
            // includes
            if(preg_match_all("#\{include \"(.+?)\"\}#i", $codes[$i], $m))
            {
                for($a = 0; $a < count($m[0]); $a++)
                {
                    $codes[$i] = str_replace($m[0][$a], "' . (\$this->fetch('".$m[1][$a]."')).'", $codes[$i]);
                }
            }
            // loops
            if(preg_match_all("#\{loop name=([a-zA-Z0-9_]+?) var=\\$([a-zA-Z0-9_\.\[\]]+?)\}#i", $codes[$i], $m))
            {
                for($a = 0; $a < count($m[0]); $a++)
                {
                    $codes[$i] = str_replace($m[0][$a],   "';for($" . $m[1][$a] . '=0; $' . $m[1][$a] . '<count(' . $this->_ref($m[2][$a],0) . '); $' . $m[1][$a] . "++){print'", $codes[$i]);
                    $codes[$i] = rtrim($codes[$i]);
                }
            }
            // rowcolor
            $codes[$i] = preg_replace("#\{rowcolor\:(.+?)\|(.+?)\|(.+?)\}#", "' . ((\$$1 & 1) ? '$3' : '$2') . '", $codes[$i]);
            // comments
            if(preg_match_all("#\{\*.*?\*\}#i", $codes[$i], $m))
            {
                for($a = 0; $a < count($m[0]); $a++)
                {
                    $codes[$i] = str_replace($m[0][$a],  '', $codes[$i]);
                }
            }
            // end loop
            $codes[$i] = str_replace('{/loop}', "';}print'", $codes[$i]);
        } // codes
        //print "print '" . implode('', $codes) . "';";
        return "print '" . implode('', $codes) . "';";
    }
    
    function _ref($s, $e = 1)
    {
        $r = $e ? '@$_vars' : '$_vars';
        $a = explode('.', $s);
        if(count($a) == 1)
        {
            if(preg_match("#([a-zA-Z0-9_]+?)\[(.+?)\]#i", $s, $m))
            {
                $r .=  "['" . $m[1] . "']" . '[$' . $m[2] . ']';
                return $r;
            }
            return $r .= "['$s']";
        }
        for($i = 0; $i < count($a); $i++)
        {
            if(preg_match("#([a-zA-Z0-9_]+?)\[(.+?)\]#i", $a[$i], $m))
            {
                $r .="['" . $m[1] . "']" . '[$' . $m[2] . ']';
            }
            else
            {
                $r .= '[\'' . $a[$i] . '\']';
            }
        }
        return  $r;
    }
    
    function lowercase ($str)
    {
        return strtolower ($str);
    }
    function uppercase ($str)
    {
        return strtoupper ($str);
    }
    function yes_or_no ($bool)
    {
        return $bool ? 'Yes' : 'No';
    }

}
?>
