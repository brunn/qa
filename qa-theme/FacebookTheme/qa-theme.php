<?php

/*
	Question2Answer (c) Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-theme/Snow/qa-theme.php
	Version: See define()s at top of qa-include/qa-base.php
	Description: Override some theme functions for Snow theme


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

	class qa_html_theme extends qa_html_theme_base
	{	

		function head_script() // change style of WYSIWYG editor to match theme better
		{
			qa_html_theme_base::head_script();
			
			$this->output(
				'<SCRIPT TYPE="text/javascript"><!--',
				"if (typeof qa_wysiwyg_editor_config == 'object')",
				"\tqa_wysiwyg_editor_config.skin='kama';",
				'//--></SCRIPT>'
			);
		}
		
		function logged_in() 
		{
		$this->output('<a class="qa-nav-ask-link" id="askButton" href="/Question2Answer/ask/"><img src="'.$this->rooturl.'images/ask.png" alt="" width="24" height="24" border="0" title="Ask A Question"/></a>');
		$this->output('<span class="qa-logged-in-link">');
			if (qa_is_logged_in()) // output user avatar to login bar
				$this->output(
					'<DIV CLASS="qa-logged-in-avatar">',
					QA_FINAL_EXTERNAL_USERS
					? qa_get_external_avatar_html(qa_get_logged_in_userid(), 50, true)
					: qa_get_user_avatar_html(qa_get_logged_in_flags(), qa_get_logged_in_email(), qa_get_logged_in_handle(),
						qa_get_logged_in_user_field('avatarblobid'), qa_get_logged_in_user_field('avatarwidth'), qa_get_logged_in_user_field('avatarheight'),
						50, true),
            		'</DIV>'
            	);			
			qa_html_theme_base::logged_in();
			$this->output('</span>');
			if (qa_is_logged_in())
			$this->output('<a class="qa-profile-edit" href="/Question2Answer/account/">Edit Profile</a>');		
		}
	
		function body_header() // adds login bar, user navigation and search at top of page in place of custom header content
		{		
			$this->output('<div id="qa-login-bar"><div id="qa-login-group">');
			$this->header();
			$this->nav_user_search();
            $this->output('</div></div>');
        }
		
		function nav_user_search()
		{
			$this->search();
			$this->nav('user');
		}
		
		function header() // removes user navigation and search from header and replaces with custom header content. Also opens new 
		{	
			$this->output('<DIV CLASS="qa-header">');
			$this->logo();						
			$this->header_clear();
			$this->output('</DIV> <!-- END qa-header -->', '');
		}
		
		function body_content()
		{					
			$this->body_prefix();
			$this->notices();
			$this->output('<DIV CLASS="qa-body-wrapper">', '');
			$this->output('<DIV CLASS="leftCol">');
			$this->leftpanel();
			$this->output('</DIV>');
			$this->output('<DIV CLASS="RightCol">');
			$this->main();
			$this->sidepanel();
			$this->output('</DIV>');
			$this-> widgets('full', 'low');
			$this->footer();
			$this->widgets('full', 'bottom');
			$this->output('</DIV>');
			$this->body_suffix();
		}
		
		function leftpanel() 
		{	
			$this->output('<DIV CLASS="qa-leftpanel">');
			
			if (qa_is_logged_in())
			$this->output('<DIV CLASS="qa-welcome-box">');
			$this->logged_in();
			$this->output('</DIV>');
			
			$this->nav_main_sub();
			$this->output('</DIV>');
		}
		
		function nav_main_sub()
		{
			$this->nav('main');
			$this->nav('sub');
		}
		

		
		function sidepanel() // removes sidebar for user profile pages
		{
			if ($this->template!='user')
				qa_html_theme_base::sidepanel();
		}	

		function q_item_stats($q_item)
		{
			$this->output('<DIV CLASS="qa-q-item-stats">');
			$this->post_avatar($q_item, 'qa-q-item');
			$this->output('</DIV>');
		}
		
		function q_item_main($q_item)
		{
			$this->output('<DIV CLASS="qa-q-item-main">');
			$this->q_item_title($q_item);
			$this->q_item_content($q_item);
			$this->output('<DIV CLASS="qa-q-item-meta">');
			$this->a_count($q_item);
			$this->output('</DIV>');
			$this->output('<DIV class="qa-q-item-meta">');
			$this->output( 
                '<SPAN CLASS="qa-view-count-data">', 
                $this->short_num($q_item['raw']['views']), 
                '</SPAN>' 
            ); 
			$this->output('<span class="qa-view-count-pad">views</span>');
			$this->output('</DIV>');
			$this->post_meta($q_item, 'qa-q-item');
			$this->post_tags($q_item, 'qa-q-item');
			$this->q_item_buttons($q_item);
			$this->output('</DIV>');
		}
		
		function view_count($q_item) // prevent display of view count in the usual place
		{
		}
		
		function short_num($num, $precision = 0) { 
           if ($num >= 1000 && $num < 1000000) { 
            $n_format = number_format($num/1000,$precision).'K'; 
            } else if ($num >= 1000000 && $num < 1000000000) { 
            $n_format = number_format($num/1000000,$precision).'M'; 
           } else if ($num >= 1000000000) { 
           $n_format=number_format($num/1000000000,$precision).'B'; 
           } else { 
           $n_format = $num; 
            } 
          return $n_format; 
        }
		
		function q_view_stats($q_view)
		{
			$this->output('<DIV CLASS="qa-q-view-stats">');
			$this->post_avatar($q_view, 'qa-q-view');
			$this->output('</DIV>');
		}
		
		function q_view_main($q_view)
		{
			$this->output('<DIV CLASS="qa-q-view-main">');

			if (isset($q_view['main_form_tags']))
				$this->output('<FORM '.$q_view['main_form_tags'].'>'); // form for buttons on question

			$this->q_view_content($q_view);
			$this->q_view_extra($q_view);
			$this->q_view_follows($q_view);
			$this->q_view_closed($q_view);
			$this->post_tags($q_view, 'qa-q-view');
			$this->post_meta($q_view, 'qa-q-view');
			$this->q_view_buttons($q_view);
			$this->output('<DIV CLASS="qa-q-view-votes">');
			$this->voting($q_view, 'qa-q-view');
			$this->output('</DIV>');
			$this->c_list(@$q_view['c_list'], 'qa-q-view');
			
			if (isset($q_view['main_form_tags'])) {
				$this->form_hidden_elements(@$q_view['buttons_form_hidden']);
				$this->output('</FORM>');
			}
			
			$this->c_form(@$q_view['c_form']);
			
			$this->output('</DIV> <!-- END qa-q-view-main -->');
		}
		
		function a_list_item($a_item)
		{
			$extraclass=@$a_item['classes'].($a_item['hidden'] ? ' qa-a-list-item-hidden' : ($a_item['selected'] ? ' qa-a-list-item-selected' : ''));
			
			$this->output('<DIV CLASS="qa-a-list-item '.$extraclass.'" '.@$a_item['tags'].'>');

			$this->output('<DIV CLASS="qa-q-view-stats">');
			$this->post_avatar($a_item, 'qa-a-item');
			$this->output('</DIV>');
			$this->a_item_main($a_item);
			$this->a_item_clear();

			$this->output('</DIV> <!-- END qa-a-list-item -->', '');
		}
		
		function a_item_main($a_item)
		{
			$this->output('<DIV CLASS="qa-a-item-main">');
			
			if (isset($a_item['main_form_tags']))
				$this->output('<FORM '.$a_item['main_form_tags'].'>'); // form for buttons on answer

			if ($a_item['hidden'])
				$this->output('<DIV CLASS="qa-a-item-hidden">');
			elseif ($a_item['selected'])
				$this->output('<DIV CLASS="qa-a-item-selected">');

			$this->a_selection($a_item);
			$this->error(@$a_item['error']);
			$this->a_item_content($a_item);
			$this->post_meta($a_item, 'qa-a-item');
			
			if ($a_item['hidden'] || $a_item['selected'])
				$this->output('</DIV>');
			
			$this->a_item_buttons($a_item);
			
			$this->output('<DIV CLASS="qa-q-view-votes">');
			if (isset($a_item['main_form_tags']))
				$this->output('<FORM '.$a_item['main_form_tags'].'>'); // form for voting buttons
			
			$this->voting($a_item);
			
			if (isset($a_item['main_form_tags'])) {
				$this->form_hidden_elements(@$a_item['voting_form_hidden']);
				$this->output('</FORM>');
			}
			$this->output('</DIV>');
			
			$this->c_list(@$a_item['c_list'], 'qa-a-item');

			if (isset($a_item['main_form_tags'])) {
				$this->form_hidden_elements(@$a_item['buttons_form_hidden']);
				$this->output('</FORM>');
			}

			$this->c_form(@$a_item['c_form']);

			$this->output('</DIV> <!-- END qa-a-item-main -->');
		}
		
		
		function message_item($message)
		{
			$this->output('<DIV CLASS="qa-message-item" '.@$message['tags'].'>');
			$this->output('<DIV CLASS="qa-message-item-right">');
			$this->message_buttons($message);
			$this->output('</DIV>');
			$this->output('<DIV CLASS="qa-message-item-left">');
			$this->post_avatar($message, 'qa-message');
			$this->output('</DIV>');
			$this->output('<DIV CLASS="qa-message-item-center">');
			$this->message_content($message);
			$this->post_meta($message, 'qa-message');
			$this->output('</DIV>');
			$this->output('</DIV> <!-- END qa-message-item -->', '');
		}
		
		function c_list_item($c_item)
		{
			$extraclass=@$c_item['classes'].(@$c_item['hidden'] ? ' qa-c-item-hidden' : '');
			
			$this->output('<DIV CLASS="qa-c-list-item '.$extraclass.'" '.@$c_item['tags'].'>');
			$this->output('<DIV CLASS="qa-c-item-left">');
			$this->post_avatar($c_item, 'qa-c-item');
			$this->output('</DIV>');
			$this->output('<DIV CLASS="qa-c-item-right">');
			$this->c_item_main($c_item);
			$this->output('</DIV>');
			$this->c_item_clear();

			$this->output('</DIV> <!-- END qa-c-item -->');
		}
		
		function c_item_main($c_item)
		{
			$this->error(@$c_item['error']);

			if (isset($c_item['expand_tags']))
				$this->c_item_expand($c_item);
			elseif (isset($c_item['url']))
				$this->c_item_link($c_item);
			else
				$this->c_item_content($c_item);
			
			$this->output('<DIV CLASS="qa-c-item-footer">');
			$this->post_meta($c_item, 'qa-c-item');
			$this->c_item_buttons($c_item);
			$this->output('</DIV>');
		}
		
	function vote_buttons($post)
	{
	    if ((isset($this->content) && @$this->template != 'question') || isset($post['remove_votes'])) return;

	    $this->output('<DIV CLASS="qa-vote-buttons '.(($post['vote_view']=='updown') ? 'qa-vote-buttons-updown' : 'qa-vote-buttons-net').'">');

	    switch (@$post['vote_state'])
	    {
		    case 'voted_up':
			    $this->post_hover_button($post, 'vote_up_tags', '+', 'qa-vote-one-button qa-voted-up');
			    $this->post_disabled_button($post, 'vote_down_tags', '', 'qa-vote-second-button qa-vote-down');
			    break;
			    
		    case 'voted_up_disabled':
			    $this->post_disabled_button($post, 'vote_up_tags', '+', 'qa-vote-one-button qa-vote-up');
			    $this->post_disabled_button($post, 'vote_down_tags', '', 'qa-vote-second-button qa-vote-down');
			    break;
			    
		    case 'voted_down':
			    $this->post_disabled_button($post, 'vote_up_tags', '', 'qa-vote-first-button qa-vote-up');
			    $this->post_hover_button($post, 'vote_down_tags', '&ndash;', 'qa-vote-one-button qa-voted-down');
			    break;
			    
		    case 'voted_down_disabled':
			    $this->post_disabled_button($post, 'vote_up_tags', '', 'qa-vote-first-button qa-vote-up');
			    $this->post_disabled_button($post, 'vote_down_tags', '&ndash;', 'qa-vote-one-button qa-vote-down');
			    break;
			    
		    case 'enabled':
			    $this->post_hover_button($post, 'vote_up_tags', '+', 'qa-vote-first-button qa-vote-up');
			    $this->post_hover_button($post, 'vote_down_tags', '&ndash;', 'qa-vote-second-button qa-vote-down');
			    break;

		    default:
			    $this->post_disabled_button($post, 'vote_up_tags', '', 'qa-vote-first-button qa-vote-up');
			    $this->post_disabled_button($post, 'vote_down_tags', '', 'qa-vote-second-button qa-vote-down');
			    break;
	    }

	    $this->output('</DIV>');
	}
		
		
		function attribution()
		{
			$this->output(
				'<DIV CLASS="qa-attribution">',
				'&copy; '.date('Y').' '.$this->content['site_title'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Powered by <A HREF="http://www.question2answer.org" target="_blank">Question2Answer</A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Facebook Theme by <A HREF="http://www.askoverflow.com" target="_blank">Askoverflow.com</A> & <A HREF="http://www.gumdi.com" target="_blank">Gumdi.com</A> ',
				'</DIV>'
			);
		}
		
	}


/*
	Omit PHP closing tag to help avoid accidental output
*/