<?php
/**
 *
 * @wordpress-plugin
 * Plugin Name:       Magic Sitemaps
 * Plugin URI:        http://insfires.com/detail/magic-wallpress-sitemaps/
 * Description:       Magic sitemaps for wallpaper blog, especially for blog that builded with Magic WallPress.
 * Version:           1.0.1
 * Author:            Pijar Inspirator
 * Author URI:        https://www.facebook.com/theNext.Inspirator
 * Text Domain:       sitemaps-mwp
 */
function activate_magic_sitemaps() {
    add_option('insfires_smwp', array(
            'post_sitemap' => true,
            'post_changefreq' => 'daily',
            'post_priority' => '0.6',
            'post_max_url' => 100,

            'attachment_sitemap' => true,
            'attachment_changefreq' => 'daily',
            'attachment_priority' => '0.6',
            'attachment_max_url' => 1000,

            'tag_sitemap' => true,
            'tag_changefreq' => 'weekly',
            'tag_priority' => '0.4',
            'tag_max_url' => 1000,

            'category_sitemap' => true,
            'category_changefreq' => 'weekly',
            'category_priority' => '0.4',
        )
    );
}
register_activation_hook( __FILE__, 'activate_magic_sitemaps' );
new MwpSitemapBuilder();

class MwpSitemapBuilder {

    private $charset,$loc_per_page,$home_page,$options;
    public function __construct( ) {
        $this->charset =  get_bloginfo( 'charset' );
        $this->loc_per_page = 1000;
        $this->home_page = home_url();
        $this->options = get_option('smwp_options',array());
        $this->settings = get_option( 'insfires_smwp',array());
        add_action( 'init', array( $this, 'rewrite' ) );
        add_filter( 'template_redirect', array( $this, 'template' ) );
        add_filter( 'query_vars', array( $this, 'query_vars' ) );
        add_filter('redirect_canonical', array( $this, 'redirect_canonical' ));
        add_action( 'admin_menu', array($this,'admin_menu') );
        
    }

    function admin_menu(){
        add_options_page( 'Magic Sitemaps', 'Magic Sitemaps','manage_options', 'sitemaps-mwp',array('MwpSitemapPage','index'));
    }


    function rewrite() {
        add_rewrite_rule(
            '(post|attachment|category|post_tag)-sitemap([0-9]+)?\.xml$',
            'index.php?mwp_sitemap=$matches[1]&sitemap_page=$matches[2]',
            'top'
        );
        add_rewrite_rule(
            'sitemap_index.xml',
            'index.php?mwp_sitemap=1',
            'top'
        );
        add_rewrite_rule(
            'mwp-sitemap.xsl',
            'index.php?mwp_sitemap=xsl',
            'top'
        );
    }

    function template() {
        $sitemap_type = get_query_var('mwp_sitemap');
        if($sitemap_type == 'xsl'){
            header( $this->http_protocol() . ' 200 OK', true, 200 );
            header( 'X-Robots-Tag: noindex, follow', true );
            header( 'Content-Type: text/xml' );

            // Make the browser cache this file properly.
            $expires = YEAR_IN_SECONDS;
            header( 'Pragma: public' );
            header( 'Cache-Control: maxage=' . $expires );
            header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', ( time() + $expires ) ) . ' GMT' );
            require_once plugin_dir_path( __FILE__ ) . 'sitemap-xsl.php';
            echo $stylesheet;
            exit;
        }elseif($sitemap_type != null){
            $sitemap_page = get_query_var('sitemap_page');
            $settings = $this->settings;
            $not_found = false;
            if($sitemap_page == null) $sitemap_page =1;
            switch($sitemap_type){
                case 'post_tag':
                    if($settings['tag_sitemap'])
                        $this->tag_sitemap($sitemap_page);
                    else
                        $not_found = true;
                    break;
                case 'post':
                    if($settings['post_sitemap'])
                        $this->post_sitemap($sitemap_page);
                    else
                        $not_found = true;
                    break;
                case 'attachment':
                    if($settings['attachment_sitemap'])
                        $this->attachment_sitemap($sitemap_page);
                    else
                        $not_found = true;
                    break;
                case 1:
                    if(!$settings['post_sitemap'] && !$settings['tag_sitemap'] && !$settings['attachment_sitemap'] && !$settings['category_sitemap'])
                        $not_found = true;
                    else
                        $this->index_sitemap();
                    break;
                case 'category':
                    if($settings['category_sitemap'])
                        $this->category_sitemap($sitemap_page);
                    else
                        $not_found = true;
                    break;
                default :
                    $not_found = true;
                    break;
            }
            if($not_found){
                $GLOBALS['wp_query']->set_404();
                status_header( 404 );
            }
        }

    }

    private function index_sitemap(){
        $settings = $this->settings;
        $sitemap = '';
        $date = date('c');
        if($settings['post_sitemap']){
            $post = ceil((wp_count_posts()->publish)/$settings['post_max_url']);
            if($post == 1){
                $last_mod = (isset($this->options['sitemap_modified']['post'][1]) && !empty($this->options['sitemap_modified']['post'][1])) ? date('c',strtotime($this->options['sitemap_modified']['post'][1])) : $date;
                $sitemap .= "\t<sitemap>\n";
                $sitemap .= "\t\t<loc>".$this->home_page."/post-sitemap.xml</loc>\n";
                $sitemap .= "\t\t<lastmod>".$last_mod."</lastmod>\n";
                $sitemap .= "\t</sitemap>\n";
            }elseif($post > 1){
                for($i=1;$i<=$post;$i++){
                    $last_mod = (isset($this->options['sitemap_modified']['post'][$i]) && !empty($this->options['sitemap_modified']['post'][$i])) ? date('c',strtotime($this->options['sitemap_modified']['post'][$i])) : $date;
                    $sitemap .= "\t<sitemap>\n";
                    $sitemap .= "\t\t<loc>".$this->home_page."/post-sitemap$i.xml</loc>\n";
                    $sitemap .= "\t\t<lastmod>".$last_mod."</lastmod>\n";
                    $sitemap .= "\t</sitemap>\n";
                }
                unset($i);
            }
        }
        
        if($settings['attachment_sitemap']){
            $attachment = ceil((wp_count_posts('attachment')->inherit)/$settings['attachment_max_url']);
            if($attachment == 1){
                $last_mod = (isset($this->options['sitemap_modified']['attachment'][1]) && !empty($this->options['sitemap_modified']['attachment'][1])) ? date('c',strtotime($this->options['sitemap_modified']['attachment'][1])) : $date;
                $sitemap .= "\t<sitemap>\n";
                $sitemap .= "\t\t<loc>".$this->home_page."/attachment-sitemap.xml</loc>\n";
                $sitemap .= "\t\t<lastmod>".$last_mod."</lastmod>\n";
                $sitemap .= "\t</sitemap>\n";
            }elseif($attachment > 1){
                for($i=1;$i<=$attachment;$i++){
                    $last_mod = (isset($this->options['sitemap_modified']['attachment'][$i]) && !empty($this->options['sitemap_modified']['attachment'][$i])) ? date('c',strtotime($this->options['sitemap_modified']['attachment'][$i])) : $date;
                    $sitemap .= "\t<sitemap>\n";
                    $sitemap .= "\t\t<loc>".$this->home_page."/attachment-sitemap$i.xml</loc>\n";
                    $sitemap .= "\t\t<lastmod>".$last_mod."</lastmod>\n";
                    $sitemap .= "\t</sitemap>\n";
                }
                unset($i);
            }
        }
        
        if($settings['tag_sitemap']){
           $this->options['post_tags'] = array();
            $articles = get_posts(
                array(
                    'numberposts' => -1,
                    'post_status' => 'publish',
                    'post_type' => 'post',
                    'orderby'          => 'date',
                    'order'            => 'ASC',
                )
            );
            if(!empty($articles)){
                $i = 1;
                $count_tags = 0;
                foreach($articles as $post){
                    $this->options['post_tags'][$i][] = $post->ID;
                    $post_tags = wp_get_post_tags($post->ID);
                    $count_tags = $count_tags+count($post_tags);
                    if($count_tags >= $settings['tag_max_url']){
                        $i++;
                        $count_tags = 0;
                    }
                }
                unset($i);
                foreach($this->options['post_tags'] as $z=>$tags){
                    $last_mod = (isset($this->options['sitemap_modified']['post_tag'][$z]) && !empty($this->options['sitemap_modified']['post_tag'][$z])) ? date('c',strtotime($this->options['sitemap_modified']['post_tag'][$z])) : $date;
                    $sitemap .= "\t<sitemap>\n";
                    $sitemap .= "\t\t<loc>".$this->home_page."/post_tag-sitemap$z.xml</loc>\n";
                    $sitemap .= "\t\t<lastmod>".$last_mod."</lastmod>\n";
                    $sitemap .= "\t</sitemap>\n";
                }
                unset($z);
            } 
        }
        
        update_option('smwp_options',$this->options);

        if($settings['category_sitemap']){
            $sitemap .= "\t<sitemap>\n";
            $sitemap .= "\t\t<loc>".$this->home_page."/category-sitemap.xml</loc>\n";
            $sitemap .= "\t\t<lastmod>".$date."</lastmod>\n";
            $sitemap .= "\t</sitemap>\n"; 
        }
        
        if ( ! headers_sent() ) {
            header( $this->http_protocol() . ' 200 OK', true, 200 );
            // Prevent the search engines from indexing the XML Sitemap.
            header( 'X-Robots-Tag: noindex,follow', true );
            header( 'Content-Type: text/xml' );
        }
        echo '<?xml version="1.0" encoding="'.esc_attr( $this->charset ).'"?><?xml-stylesheet type="text/xsl" href="'.preg_replace( '/(^http[s]?:)/', '', esc_url( home_url( 'mwp-sitemap.xsl' ) ) ) .'"?>'."\n";
        echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        echo $sitemap;
        echo "</sitemapindex>";
        exit;

    }

    private function category_sitemap($page){
        $settings = $this->settings;
        if($page == 1 && $settings['category_sitemap']){
            $categories = get_terms( 'category', array(
                'orderby'    => 'name',
            ) );
            $sitemap ='';
            if(!empty($categories)){
                foreach($categories as $category){
                    $sitemap .= "\t<url>\n";
                    $sitemap .= "\t\t<loc>".get_category_link($category->term_id)."</loc>\n";
                    $sitemap .= "\t\t<changefreq>".$settings['category_changefreq']."</changefreq>\n";
                    $sitemap .= "\t\t<priority>".$settings['category_priority']."</priority>\n";
                    $sitemap .= "\t</url>";
                }
                if ( ! headers_sent() ) {
                    header( $this->http_protocol() . ' 200 OK', true, 200 );
                    // Prevent the search engines from indexing the XML Sitemap.
                    header( 'X-Robots-Tag: noindex,follow', true );
                    header( 'Content-Type: text/xml' );
                }
                echo '<?xml version="1.0" encoding="'.esc_attr( $this->charset ).'"?><?xml-stylesheet type="text/xsl" href="'.preg_replace( '/(^http[s]?:)/', '', esc_url( home_url( 'mwp-sitemap.xsl' ) ) ) .'"?>'."\n";
                echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
                echo $sitemap;
                echo "\n</urlset>";
                exit;
            }
        }
            $GLOBALS['wp_query']->set_404();
            status_header( 404 );
    }

    private function tag_sitemap($page){
        $options = $this->options;
        $settings = $this->settings;
        $sitemap ='';
        $posts = $options['post_tags'][$page];
        if(!empty($posts) && $settings['tag_sitemap']){
            foreach($posts as $i=>$post_id){
                $last_mod = get_the_date( 'c', $post_id );
                $post_tags = wp_get_post_tags($post_id,array( 'fields' => 'ids' ));
                foreach($post_tags as $tags){
                    $sitemap .= "\t<url>\n";
                    $sitemap .= "\t\t<loc>".get_term_link($tags,'post_tag')."</loc>\n";
                    $sitemap .= "\t\t<lastmod>$last_mod</lastmod>\n";
                    $sitemap .= "\t\t<changefreq>".$settings['tag_changefreq']."</changefreq>\n";
                    $sitemap .= "\t\t<priority>".$settings['tag_priority']."</priority>\n";
                    $sitemap .= "\t</url>";
                }
            }
            $options['sitemap_modified']['post_tag'][$page]= date('Y-m-d H:i:s');
            update_option('smwp_options',$options);
            if ( ! headers_sent() ) {
                header( $this->http_protocol() . ' 200 OK', true, 200 );
                // Prevent the search engines from indexing the XML Sitemap.
                header( 'X-Robots-Tag: noindex,follow', true );
                header( 'Content-Type: text/xml' );
            }
            echo '<?xml version="1.0" encoding="'.esc_attr( $this->charset ).'"?><?xml-stylesheet type="text/xsl" href="'.preg_replace( '/(^http[s]?:)/', '', esc_url( home_url( 'mwp-sitemap.xsl' ) ) ) .'"?>'."\n";
            echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
            echo $sitemap;
            echo "\n</urlset>";
            exit;
        }else{
            $GLOBALS['wp_query']->set_404();
            status_header( 404 );
        }
    }

    private function post_sitemap($page){
        $settings = $this->settings;
        if($settings['post_sitemap']){
            $args = array(
                'posts_per_page'   => $settings['post_max_url'],
                'paged'            => $page,
                'orderby'          => 'date',
                'order'            => 'ASC',
                'post_type'        => 'post',
                'post_status'      => 'publish',
            );
            $options = $this->options;
            $sitemap ='';
            $posts = get_posts($args);
            if(!empty($posts)){
                foreach($posts as $post){
                    $sitemap .= "\t<url>\n";
                    $sitemap .= "\t\t<loc>".get_permalink($post->ID)."</loc>\n";
                    $sitemap .= "\t\t<lastmod>".date('c',strtotime($post->post_modified))."</lastmod>\n";
                    $sitemap .= "\t\t<changefreq>".$settings['post_changefreq']."</changefreq>\n";
                    $sitemap .= "\t\t<priority>".$settings['post_priority']."</priority>\n";
                    $sitemap .= $this->get_attachments($post->ID);
                    $sitemap .= "\t</url>";
                }
                $options['sitemap_modified']['post'][$page]= date('Y-m-d H:i:s');
                update_option('smwp_options',$options);
                if ( ! headers_sent() ) {
                    header( $this->http_protocol() . ' 200 OK', true, 200 );
                    // Prevent the search engines from indexing the XML Sitemap.
                    header( 'X-Robots-Tag: noindex,follow', true );
                    header( 'Content-Type: text/xml' );
                }
                echo '<?xml version="1.0" encoding="'.esc_attr( $this->charset ).'"?><?xml-stylesheet type="text/xsl" href="'.preg_replace( '/(^http[s]?:)/', '', esc_url( home_url( 'mwp-sitemap.xsl' ) ) ) .'"?>'."\n";
                echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
                echo $sitemap;
                echo "\n</urlset>";
                exit;
            }else{
                $GLOBALS['wp_query']->set_404();
                status_header( 404 );
            }
        }else{
            $GLOBALS['wp_query']->set_404();
            status_header( 404 );
        }
    }

    private function attachment_sitemap($page){
        $settings = $this->settings;
        if($settings['attachment_sitemap']){
            $args = array(
                'posts_per_page'   => $settings['attachment_max_url'],
                'paged'            => $page,
                'orderby'          => 'date',
                'order'            => 'ASC',
                'post_type'        => 'attachment',
                'post_mime_type' => 'image',
            );
            $options = $this->options;
            $sitemap ='';
            $posts = get_posts($args);
            if(!empty($posts)){
                foreach($posts as $post){
                    $link = get_attachment_link($post->ID);
                    if(strpos( $link, '?attachment_id=' ) === false){
                        $src = wp_get_attachment_image_src( $post->ID, 'full' );
                        $sitemap .= "\t<url>\n";
                        $sitemap .= "\t\t<loc>".$link."</loc>\n";
                        $sitemap .= "\t\t<lastmod>".date('c',strtotime($post->post_modified))."</lastmod>\n";
                        $sitemap .= "\t\t<changefreq>".$settings['attachment_changefreq']."</changefreq>\n";
                        $sitemap .= "\t\t<priority>".$settings['attachment_priority']."</priority>\n";
                        $sitemap .= "\t\t<image:image>\n";
                        $sitemap .= "\t\t\t<image:loc>".$src[0]."</image:loc>\n";
                        $sitemap .= "\t\t\t<image:title><![CDATA[" . _wp_specialchars( html_entity_decode( $post->post_title, ENT_QUOTES, $this->charset ) ) . "]]></image:title>\n";
                        $sitemap .= "\t\t\t<image:caption><![CDATA[" . _wp_specialchars( html_entity_decode( $post->post_title, ENT_QUOTES, $this->charset ) ) . "]]></image:caption>\n";
                        $sitemap .= "\t\t</image:image>\n";
                        $sitemap .= "\t</url>";
                    }
                    unset($src,$link);
                }
                $options['sitemap_modified']['attachment'][$page]= date('Y-m-d H:i:s');
                update_option('smwp_options',$options);
                if ( ! headers_sent() ) {
                    header( $this->http_protocol() . ' 200 OK', true, 200 );
                    // Prevent the search engines from indexing the XML Sitemap.
                    header( 'X-Robots-Tag: noindex,follow', true );
                    header( 'Content-Type: text/xml' );
                }
                echo '<?xml version="1.0" encoding="'.esc_attr( $this->charset ).'"?><?xml-stylesheet type="text/xsl" href="'.preg_replace( '/(^http[s]?:)/', '', esc_url( home_url( 'mwp-sitemap.xsl' ) ) ) .'"?>'."\n";
                echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
                echo $sitemap;
                echo "\n</urlset>";
                exit;
            }else{
                $GLOBALS['wp_query']->set_404();
                status_header( 404 );
            }
        }else{
            $GLOBALS['wp_query']->set_404();
            status_header( 404 );
        }
    }

    private function get_attachments($post_id){
        $image_attachments = get_children(
            array(
                'post_parent' => $post_id,
                'post_type' => 'attachment',
                'numberposts' => -1,
                'post_mime_type' => 'image',
            )
        );
        $sitemap = '';
        if(!empty($image_attachments)){
            foreach($image_attachments as $image){
                $src = wp_get_attachment_image_src( $image->ID, 'full' );
                $sitemap .= "\t\t<image:image>\n";
                $sitemap .= "\t\t\t<image:loc>".$src[0]."</image:loc>\n";
                $sitemap .= "\t\t\t<image:title><![CDATA[" . _wp_specialchars( html_entity_decode( $image->post_title, ENT_QUOTES, $this->charset ) ) . "]]></image:title>\n";
                $sitemap .= "\t\t\t<image:caption><![CDATA[" . _wp_specialchars( html_entity_decode( $image->post_title, ENT_QUOTES, $this->charset ) ) . "]]></image:caption>\n";
                $sitemap .= "\t\t</image:image>\n";
                unset($src);
            }
        }
        return $sitemap;
    }

    function query_vars($vars) {
        $vars[] = 'mwp_sitemap';
        $vars[] = 'sitemap_page';
        return $vars;
    }

    function redirect_canonical(){
        $var = get_query_var('mwp_sitemap');
        if(!empty($var))
            return false;
    }

    private function http_protocol() {
        return ( isset( $_SERVER['SERVER_PROTOCOL'] ) && $_SERVER['SERVER_PROTOCOL'] !== '' ) ? sanitize_text_field( $_SERVER['SERVER_PROTOCOL'] ) : 'HTTP/1.1';
    }

}

class MwpSitemapPage {
    public function index(){
        $settings = get_option( 'insfires_smwp',array());
        if(isset($_POST['submit'])){
            $new = $_POST['smwp'];
            if(!isset($new['post_sitemap'])){
                $new['post_sitemap'] = 0;
            }
            if(!isset($new['attachment_sitemap'])){
                $new['attachment_sitemap'] = 0;
            }
            if(!isset($new['tag_sitemap'])){
                $new['tag_sitemap'] = 0;
            }
            if(!isset($new['category_sitemap'])){
                $new['category_sitemap'] = 0;
            }
            $settings = $new;
            update_option('insfires_smwp',$settings);
            $report = '<div class="updated"><p>Settings saved successfully</p></div>';
        }
        require_once plugin_dir_path( __FILE__ ) . 'admin.php';
    }
}