<?php

/**
 * Action related functions are listed here.
 * 
 */

        
    /**
     * Register an action in ClipBucket, this actions can be called in 
     * different functions like play_video, show_rating etc..
     * 
     * @link 
     * @global type $Cbucket
     * @param type $name
     * @param type $type 
     */
        
    function register_action($name,$type=NULL)
    {
        
        global $Cbucket;
        if(!$type) return false;
        
        if(is_array($type))
        {
            foreach($type as $t)
            {
                if(is_array($name))
                {
                    foreach($name as $n)
                       $Cbucket->actionList[$t][] = $n;
                }else
                {
                    $Cbucket->actionList[$t][] = $name;
                }
                
            }
        }else
        {
            if(is_array($name))
            {
                foreach($name as $n)
                    $Cbucket->actionList[$type][] = $n;
            }else
            {
                $Cbucket->actionList[$type][] = $name;
                
                
            } 
        }
 
    }
	
    /**
    * FUNCTION USED TO CREATE ANCHOR PLACEMENT
    * these are the placement where we can add plugin's or widget's code,
    * e.g if we want to display a new WYSIWYG box before comment text area 
    * we will create anchor before text area as {ANCHOR place='before_compose_box'}
    * code will be written in plugin file and its place will point 'before_compose_box'
    * then our function will get all the code for this placement and will display it
    * @param : array(Ad Code, LIMIT);
    */
    function ANCHOR($params)
    {
            global $Cbucket;
            //Getting List of codes to display at this anchor
            $codes = $Cbucket->get_anchor_codes($params['place']);
            if(!empty($codes))
            {
                    if(is_array($codes))
                    {
                            foreach($codes as $code)
                            {
                                    echo $code;
                            }
                    }else{
                            echo $codes;
                    }
            }

            //Getting list of function that will be performed while calling achor
            $funcs = $Cbucket->get_anchor_function_list($params['place']);

            if(!empty($funcs))
            {
                    if(is_array($funcs))
                    {
                            foreach($funcs as $func)
                            {
                                    if(function_exists($func))
                                    {
                                            if($params['data'])
                                                    $func($params['data']);
                                            else
                                                    $func();
                                    }
                            }
                    }else{
                            if($params['data'])
                                    $funcs($params['data']);
                            else
                                    $funcs();
                    }
            }
    }

    /**
    * FUNCTION USED TO REGISTER ANCHORS
    * before_comments etc.. see complete list on http://docs.clip-bucket.com
    */
    function register_anchor($name,$type=NULL)
    {
            global $Cbucket;
            if(is_array($name))
            {
                    foreach($name as $key => $naam)
                            if(is_array($naam))
                            {
                                    foreach($naam as $name)
                                    {
                                            $Cbucket->anchorList[$name][] = $key;
                                    }
                            }else{

                                    $Cbucket->anchorList[$naam][] = $key;
                            }
            }elseif($type!=NULL){
                    $Cbucket->anchorList[$type][] = $name;
            }
    }



    /**
    * FUNCTION USED TO REGISTER FUNCTION
    * If you want to perform some function on 
    * some place, you can simple register function that will be execute where anchor points are
    * placed
    */
    function register_anchor_function($name,$type=NULL)
    {
            global $Cbucket;
            if(is_array($name))
            {
                    foreach($name as $key => $naam)
                            if(is_array($naam))
                            {
                                    foreach($naam as $name)
                                    {
                                            $Cbucket->anchor_function_list[$name][] = $key;
                                    }
                            }else{

                                    $Cbucket->anchor_function_list[$naam][] = $key;
                            }
            }elseif($type!=NULL){
                    $Cbucket->anchor_function_list[$type][] = $name;
            }
    }






    /**
        * Function used to add custom upload fields
        * In this you will provide an array that has a complete
        * details of the field such as 'name',validate_func etc
        * please check docs.clip-bucket.com for "how to add custom upload field"
        */
    function register_custom_upload_field($array)
    {
            global $Upload;
            $name = key($array);
            if(is_array($array) && !empty($array[$name]['name']))
            {
                    foreach($array as $key => $arr)
                            $Upload->custom_upload_fields[$key] = $arr;
            }
    }

    /**
        * Function used to add custom form fields
        * In this you will provide an array that has a complete
        * details of the field such as 'name',validate_func etc
        * please check docs.clip-bucket.com for "how to add custom form field"
        */
    function register_custom_form_field($array,$isGroup=false)
    {
            global $Upload;
            $name = key($array);

            if(!$isGroup)
            {
                    if(is_array($array) && !empty($array[$name]['name']))
                    {
                            foreach($array as $key => $arr)
                                    $Upload->custom_form_fields[$key] = $arr;
                    }
            }else
            {
                    if(is_array($array) && !empty($array['group_name']) )
                    {
                            $Upload->custom_form_fields_groups[] = $array;
                    }
            }
    }



    /**
        * Function used to add custom signup form fields
        * In this you will provide an array that has a complete
        * details of the field such as 'name',validate_func etc
        * please check docs.clip-bucket.com for "how to add custom signup field"
        */
    function register_signup_field($array)
    {
            global $userquery;
            $name = key($array);
            if(is_array($array) && !empty($array[$name]['name']))
            {
                    foreach($array as $key => $arr)
                            $userquery->custom_signup_fields[$key] = $arr;
            }

    }


    /**
        * Function used to add custom profile fields fields
        * In this you will provide an array that has a complete
        * details of the field such as 'name',validate_func etc
        * please check docs.clip-bucket.com for "how to add custom form field"
        */
    function register_custom_profile_field($array,$isGroup=false)
    {
            global $userquery;
            $name = key($array);

            if(!$isGroup)
            {
                    if(is_array($array) && !empty($array[$name]['name']))
                    {
                            foreach($array as $key => $arr)
                                    $userquery->custom_profile_fields[$key] = $arr;
                    }
            }else
            {
                    if(is_array($array) && !empty($array['group_name']) )
                    {
                            $userquery->custom_profile_fields_groups[] = $array;
                    }
            }
    }



    /**
        * Function used to add actions that will be performed
        * when video is uploaded
        * @param Function name
        */
        function register_after_video_upload_action($func)
        {
                global $Upload;
                $Upload->actions_after_video_upload[] = $func;
        }

        /**
        * Function used to add actions that will be performed
        * when video is going to play, it will check which player to use
        * what type to use and what to do
        * @param Function name
        */
        function register_actions_play_video($func)
        {
            register_action($func,'play_video');
        }




            function register_collection_delete_functions($func)
            {
                    global $cbcollection;
                    $cbcollection->collection_delete_functions[] = $func;	
            }



        /**
        * Function used to add links in admin area
        */
        function add_admin_link($array)
        {
                $area = $array['area'];
                $title = $array['title'];
                $link = $array['link'];
        }


        /**
        * function use to register function that will be 
        * called while deleting a video
        */
        function register_action_remove_video($func)
        {
                global $cbvid;		 
                //Editing this thing without special consideration can trun whole CB into "WTF"
                $cbvid->video_delete_functions[] = $func;
        }

        /**
        * Function used to register function , that will be called when deleting video files
        */
        function register_action_remove_video_files($func)
        {
                global $Cbucket;	 
                $Cbucket->on_delete_video[] = $func;
        } 

    /**
        * Function used to display comment rating
        */
    function comment_rating($input)
    {
        if($input<0)
            $output = $input;
        elseif($input>0)
            $output = '+'.$input;
        else
            $output = '0';   

        $output = apply_filters($output, 'comment_rating');  


        return $output;
    }


    /**
        * Function use to register security captchas for clipbucket
        */
    function register_cb_captcha($func,$ver_func,$show_field=true)
    {
            global $Cbucket;
            $Cbucket->captchas[] = array('load_function'=>$func,'validate_function'=>$ver_func,'show_field'=>$show_field);
    }register_anchor_function('cbRocks','the_footer');


    /**
        * FUnction used to register ClipBucket php functions
        */
    function cb_register_function($func_name,$place,$params=NULL)
    {
            global $Cbucket;

            if(function_exists($func_name))
            {
                    $Cbucket->clipbucket_functions[$place][] = array('func'=>$func_name,'params'=>$params);
            }
    }

    /**
        * function used to check weather specific place has function or not
        */
    function cb_get_functions($place)
    {
            global $Cbucket;
            if(count($Cbucket->clipbucket_functions[$place])>0)
            {
                    return $Cbucket->clipbucket_functions[$place];
            }else
                    return false;
    }

    /**
        * Function used to call functions
    function cb_call_functions($place)
    {
            $funcs = cb_get_functions($place);
            if(is_array($funcs))
            foreach($funcs as $func)
            {
                    $func_name = $func['func'];
                    $params = $func['params'];
                    if(function_exists($func_name))
                    {
                            if($params)
                                    $func_name($params);
                            else
                                    $func_name();
                    }
            }
    }*/

    /**
        * Function used to call cb_functions
        * Why I am re-writting this. Because we need some
        * extra parameters in some places to make these 
        * functions work correctly. Like while displaying
        * categories we need to pass all paramerters that
        * user has passed in cbCategories function
        */
    function cb_call_functions($place,$extra=NULL)
    {
        $funcs = cb_get_functions($place);
        if(is_array($funcs))
        foreach($funcs as $func)
        {
            $fname = $func['func'];
            $fparams = $func['params'];
            if(function_exists($fname))
            {
                if($fparams) // checking if we have user defined params
                {
                    if(is_array($fparams)) // Checking if params are array
                        if($extra && is_array($extra)) // Checking if we have some extra params
                            $fparams = array_merge($fparams,$extra); // If yes, then merge all params
                        else
                            $fparams = $fparams; // No Continue with user defined params	
                    elseif($extra)
                        $fparams = $extra; // It is not array, so assign $extra to $fparams.

                    if(!empty($fparams))	
                        $fname($fparams);
                    else
                        $fname();				
                } else {
                    if($extra != NULL)
                        $fname($extra);
                    else
                        $fname();		
                }
            }
        }
    }

    /**
        * Regiseter Embed Function
        */
    function register_embed_function($name)
    {
            global $cbvid;
            $cbvid->embed_func_list [] = $name;
    }

    function create_module_link($params)
    {
            $Modlink = $module = "module.php";
            $section = $params['section'];
            $page = $params['page'];
            $extra = $params['extra'];	

            if(empty($section) || empty($page))
                    $Modlink = BASEURL;
            else
            {
                    $Modlink .= "?s=".$section;
                    $Modlink .= "&p=".$page;
                    if(($extra))
                    {
                            if(is_array($extra))
                            {
                                    foreach($extra as $var=>$value)
                                    {	
                                            if(is_numeric($var))
                                                    $woIndex[] = $value;
                                            else
                                            {		
                                                    $Modlink .= "&";
                                                    $Modlink .= $var."=".$value;
                                            }
                                    }
                                    if(isset($woIndex))
                                            if(count($woIndex) > 1)
                                            {
                                                    foreach($woIndex as $var)
                                                    {
                                                            $Modlink .= "&".$var."=";	
                                                    }
                                            } else {
                                                    $Modlink .= "&".$woIndex[0]."=";						
                                            }
                                    } else {
                                            $Modlink .= "&".$extra;	
                                    }
                    }
            }

            return $Modlink;
    }

    function create_ModLink($section,$page,$extra=NULL)
    {
            $params = array("section"=>$section,"page"=>$page,"extra"=>$extra);
            return create_module_link($params);
    }


    /**
        * function used to get remote url function
        */
    function get_remote_url_function()
    {
            $funcs = cb_get_functions('remote_url_function');

            if($funcs)
            foreach($funcs as $func)
            {
                    $val = $func['func']();
                    if($val)
                            return $val;
            }
            return 'check_remote_url()';
    }
    
    
    /**
     * Function used to get list of actions for given place
     * 
     * @param STRING $place 
     */
    function get_actions($place)
    {
        global $Cbucket;
        $func_list = $Cbucket->actionList[$place];
        if($func_list)
        {
            $new_func_list = array();
            foreach($func_list as $func)
            {
                if(function_exists($func))
                {
                    $new_func_list[] = $func;
                }
            }
            
            return $new_func_list;
        }else
            return false;
    }
    
    /**
     * Call actions on a particular placement.
     * the limit here is there can be only 1 parameter that can be passed to
     * actions so you have to pass all values via ARRAY instead of adding
     * multiple params...ie call_actions('play_video',$params); 
     * $params will be passed to all functions that are registerd for
     * play_video as 1st parameter.
     */
    function call_actions($place,$param)
    {
        
        $funcs = get_actions($place);
        if($funcs)
        {
            foreach($funcs as $func)
            {
                $output = $func($param);
                if($output)
                    return $output;
            }
            return false;
        }
        
        return false;
    }
?>
