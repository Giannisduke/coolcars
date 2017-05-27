<div class="row" id="filters">

    <div class="btn-group" role="group" data-filter-group="leistung">
      <button type="button"  class="btn btn-secondary button" data-filter-value1="" data-filter-value="" class="selected">All</button>
      <?php
      $terms = get_terms("internship_cat"); // get all categories, but you can use any taxonomy
      $count = count($terms); //How many are they?
      if ( $count > 0 ){  //If there are more than 0 terms
        foreach ( $terms as $term ) {  //for each term:
          echo "<button type='button' class='btn btn-secondary button' data-filter-value1='#filter-leistung-internship_cat-".$term->slug."' data-filter-value='.internship_cat-".$term->slug."'>" . $term->name . "</button>\n";
          //create a buttonst item with the current term slug for sorting, and name for label
        }
      }
      ?>
    </div>




    <select class="selectpicker filter option-set clearfix" data-filter-group="lichtfarbe" multiple data-actions-box="true" >
      <?php
      $terms = get_terms("internship_lc"); // get all categories, but you can use any taxonomy
      $count = count($terms); //How many are they?
      if ( $count > 0 ){  //If there are more than 0 terms
        foreach ( $terms as $term ) {  //for each term:
          echo "<option data-filter-value1='#filter-leistung-".$term->slug."' data-filter-value='.internship_lc-".$term->slug."'>" . $term->name . "</option>\n";
          //create a list item with the current term slug for sorting, and name for label
        }
      }
      ?>
    </select>


</div>
