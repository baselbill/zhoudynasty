<?php
if ( !defined ( 'SHOUTBOX' ) )
{
    exit ( 'Wrong file.' );
}

class shoutbox
{
    //--------------------------------------------------
    // Vars
    //--------------------------------------------------
    var $shouts_dir = '';
    
    var $index_data = array ( );

    //--------------------------------------------------
    // Public
    //--------------------------------------------------

    function shoutbox ( $shouts_dir )
    {
        if ( !is_dir ( $shouts_dir ) )
        {
            exit ( 'error, supplied path is not a directory in ' . $shouts_dir );
        }
        if ( $shouts_dir [ strlen ( $shouts_dir ) - 1 ] !== '/' )
        {
            $shouts_dir .= '/';
        }
        $this->shouts_dir = $shouts_dir;
        
        $this->index_data = $this->_array_read ( $this->shouts_dir . 'shout_index.txt', 1 );
    }

    function add_shout ( $name, $url, $email, $message, $is_private )
    {
        if ( count ( $this->index_data ) )
        {
            $last_entry = end ( $this->index_data );
            
            $shout_id = $last_entry[0] + 1;
        }
        else
        {
            $shout_id = 1;
        }

        $shout_file = $shout_id . '.txt';
        
        $name = $this->_make_safe ( $name );
        
        $url = $this->_make_safe ( $url );
        
        $email = $this->_make_safe ( $email );
        
        $message = $this->_make_safe ( $message );
        
        $ip = $_SERVER['REMOTE_ADDR'];
        
        $time = time ( );

        $shout = array ( $shout_id, $name, $url, $email, $message, (bool)$is_private, $ip, $time );

        $this->index_data [] = array ( $shout_id, $name, $ip, $time, $shout_file );
        
        $this->_array_write ( $this->shouts_dir . $shout_file, $shout, 1 );
        
        $this->_array_write ( $this->shouts_dir . 'shout_index.txt', $this->index_data, 1 );

    }
    
    function edit_shout ( $shoutid, $name, $url, $email, $message )
    {
        $pos = $this->_search_id ( $this->index_data, $shoutid );
        
        if ( $pos === NULL )
        {
            return false;
        }
        
        $shout = $this->_array_read ( $this->shouts_dir . $this->index_data[$pos][4] );
        
        $shout[1] = $this->_make_safe( $name );
        
        $shout[2] = $this->_make_safe( $url );
        
        $shout[3] = $this->_make_safe( $email );
        
        $shout[4] = $this->_make_safe( $message );
        
        $this->_array_write ( $this->shouts_dir . $this->index_data[$pos][4], $shout, 1 );
        
        return true;
    }

    function delete_shout ( $id )
    {
        $pos = $this->_search_id ( $this->index_data, $id );
        
        if ( $pos === NULL )
        {
            return false;
        }
        else
        {
            if ( !@unlink ( $this->shouts_dir . $this->index_data[$pos][4] ) )
            {
                return false;
            }
            unset ( $this->index_data[$pos] );

            $this->index_data = array_values ( $this->index_data );

            $this->_array_write ( $this->shouts_dir . 'shout_index.txt', $this->index_data, 1 );

            return true;
        }
    }
    
    function prune_shouts ( $timeout )
    {
        $current_time = time ( );
        
        for ( $i = 0; $i < count ( $this->index_data ) && ( $current_time - $this->index_data[$i][3] ) > $timeout; $i++ );

        if ( $i == 0 )
        {
            return false;
        }
        else
        {
            $tbd = array_slice ( $this->index_data, 0, $i -1 );

            for ( $i = 0; $i < count ( $tbd ); $i++ )
            {
                unlink ( $this->shouts_dir . $tbd[$i][4] );
            }
            $this->index_data = array_slice ( $this->index_data, $i );
            
            $this->_array_write ( $this->shouts_dir . 'shout_index.txt', $this->index_data, 1 );

            return true;
        }
    }

    function get_shouts ( $start, $end )
    {
        if ( !count ( $this->index_data ) )
        {
            return array ( );
        }
        $x = ( $start < 0 ) ? array_slice ( $this->index_data, -$end ) : array_slice ( $this->index_data, $start, ( $end - $start ) );

        if ( $x )
        {
            $return = array ( );
            
            for ( $i = 0; $i < count ( $x ); $i++ )
            {
                $return [] = $this->_array_read ( $this->shouts_dir . $x [ $i ] [ 4 ] );
            }
            
            return $return;
        }

        return array ( );
    }
    
    function get_shouts_index ( $start, $end )
    {
        if ( !count ( $this->index_data ) )
        {
            return array ( );
        }
        $x = ( $start < 0 ) ? array_slice ( $this->index_data, -$end ) : array_slice ( $this->index_data, $start, ( $end - $start ) );

        if ( $x )
        {
            return $x;
        }

        return array ( );
    }
    
    function get_shout ( $id )
    {
        $pos = $this->_search_id ( $this->index_data, $id );

        if ( $pos === NULL )
        {
            return false;
        }
        else
        {
            return $this->_array_read ( $this->shouts_dir . $this->index_data[$pos][4] );
        }
    }

    
    function get_all_shouts ( )
    {
        return $this->get_shouts ( 0, $this->get_shouts_count ( ) );
    }

    
    function get_shouts_count ( )
    {
        return count ( $this->index_data );
    }
    
    function get_uniques_count ( )
    {
        $temp = array ( );

        for ( $i = 0; $i < count ( $this->index_data ); $i++ )
        {
            $temp [ ] = $this->index_data[$i][2];
        }
        
        return count ( array_unique ( $temp ) );
    }
        


    //--------------------------------------------------
    // Privates
    //--------------------------------------------------

    function _search_id ( $array, $target )
    {
        $left = 0;
        $right = count ( $array ) - 1;

        if ( $target < $array[$left][0] || $target > $array[$right][0] )
        {
            return NULL;
        }
        while ( $left < $right )
        {
            $middle = intval ( ( $left + $right ) / 2 );
            
            if ( $array[$middle][0] == $target )
            {
                return $middle;
            }
            elseif( $target < $array[$middle][0] )
            {
                $right = $middle - 1;
            }
            elseif( $target > $array[$middle][0] )
            {
                $left = $middle + 1;
            }
        }
        if( $target == $array[$right][0] )
        {
            return $right;
        }
        return NULL;
    }
   
    function &_array_read ( $file, $create_if_not_exists = 0 )
    {
    	if ( !file_exists ( $file ) )
    	{
    		if ( $create_if_not_exists )
    		{
    			fclose ( fopen ( $file, 'wb' ) );
    		}
    		else
    		{
    			exit ( 'array read error ( ): "' . $file . '" does not exists.' );
    		}
    	}
    	else
    	{
    		if ( filesize ( $file ) > 0 )
    		{
    			$fp = fopen ( $file, 'rb' );
    			$buffer = fread ( $fp, filesize ( $file ) );
    			fclose ( $fp );
    			return unserialize ( $buffer );
    		}
    		else
    		{
    			return array ( );
    		}
    	}
    }


    function _array_write ( $file, &$data, $create_if_not_exists = 0 )
    {
    	if ( !file_exists ( $file ) && !$create_if_not_exists )
    	{
    		exit ( 'write ( ): "' . $file . '" does not exists.' );
    	}
    	else
    	{
    		$fp = fopen ( $file, 'wb' );
    		flock  ( $fp, 2 );
    		fwrite ( $fp, serialize ( $data ) );
    		fclose ( $fp );
    	}
    }

    function _make_safe ( $str )
    {
        $str = str_replace ( '&amp;', '&', $str );

        $table = get_html_translation_table ( HTML_ENTITIES );
        array_shift ( $table );
        unset ( $table [ '"' ] );

        return strtr ( $str, $table );
    }
}

?>
