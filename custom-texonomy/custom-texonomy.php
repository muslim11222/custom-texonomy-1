<?php 


/**
 * Plugin name: custom texonomy
 * Description: this is my custom texonomy plugin
 * Author: Muslim khan
 * Email: sikhicode@gmail.com
 * 
 */
if ( !defined( 'ABSPATH') ) {
     exit;
}




 class custome_texonomy {
     public function __construct() {
          add_action('init', [$this, 'register_post_type']);
          add_action('init', [$this, 'register_taxonomy']);
          add_filter('the_content', [$this, 'add_movie_details']);
          add_filter('the_title', [$this, 'add_title_year'], 10, 2);
     }


     public function register_post_type() {
          register_post_type('movie', [
               'label' => 'Movie',
               'labels' => [
                    'name'          => 'Movies',
                    'singular_name' => 'movie',
                    'add_new'       => 'Add New Movie',
               ],

               'public'      => true,
               'show_ui'     => true,
               'has_archive' => true,
               'taxonomies'  => ['genre', 'actor', 'director', 'years'],
               'supports'    => ['title', 'editor', 'thumbnail'],
          ]);
     }



//register_texonomy



     public function register_taxonomy() {
          register_taxonomy('genre','movie',[
               'labels' => [
                   'name'           => 'Genre',
                   'singular_name'  => 'Genre',
                   'add_new'        => 'Add New Genre',
               ],
               'public'             => true,
               'hierarchical'       => false,
               'show_ui'            => true,
               'show_admin_column'  => true, // add new post ar jaigai show korbe 
          ]);


     
          //Actors
          register_taxonomy( 'actor', ['movie'], [

               'labels' => [
                 'name'          => 'Actors',
                 'singular_name' => 'Actor',
               ],
  
               'public'          => true,
               'show_ui'         => true,
               'hierarchical'    => true,
               'show_admin_column'  => true, // add new post ar jaigai show korbe 
          ]);


          //Directo
          register_taxonomy( 'director', ['movie'], [

               'labels' => [
                 'name'          => 'Directors',
                 'singular_name' => 'director',
               ],
  
               'public'          => true,
               'show_ui'         => true,
               'hierarchical'    => true,
               'show_admin_column'  => true, // add new post ar jaigai show korbe 
          ]);


          //Year
          register_taxonomy( 'years', ['movie'], [

               'labels' => [
                 'name'          => 'Years',
                 'singular_name' => 'Year',
               ],
               
               'rewrite' => [

                    'slug'      => 'movie-year',
               ],
               'public'         => true,
               'show_ui'        => true,
               'hierarchical'   => false,
               'show_admin_column'  => true, // add new post ar jaigai show korbe 
          ]);
     }

  


     //add_movie_details

     public function add_movie_details( $content ) {

          $post = get_post( get_the_ID() );
        
           
          if( $post->post_type !== 'movie' ) {

               return $content;
          }


          $hello = get_the_term_list( get_the_ID(), 'genre', '', ', ' );
          $actor = get_the_term_list( get_the_ID(), 'actor', '', ', ' );
          $director = get_the_term_list( get_the_ID(), 'director', '', ', ' );
          $year = get_the_term_list( get_the_ID(), 'years', '', ', ' );

          $info = '<ul>';

          if( $hello ) {
               $info .= '<li>';
               $info .= '<strong>Genre: </strong>';
               $info .= $hello;
               $info .= '</li>';
          }




          if( $actor ) {
               $info .= '<li>';
               $info .= '<strong>Actor: </strong>';
               $info .= $actor;
               $info .= '</li>';
          }




          if( $director ) {
               $info .= '<li>';
               $info .= '<strong>Director: </strong>';
               $info .= $director;
               $info .= '</li>';
          }


          if( ! is_wp_error($year)) {
               $info .= '<li>';
               $info .= '<strong>Year: </strong>';
               $info .= $year;
               $info .= '</li>';
          }

          
         return $content . $info;
     }


     //add_title_year  potita movier pase year show korbe 

       public function add_title_year($title, $id) {

          $post = get_post( get_the_ID() );
        
          if( $post->post_type !== 'movie' ) {

               return $title;
          }

          $years = get_the_terms( $post, 'years');

          if( $years ) {
               $title .= ' (' . $years[0]->name . ')';
          }

          return $title;
          
       }
}
new custome_texonomy();