<?php

namespace Drupal\as_courses\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'CoursesBlock' block.
 *
 * @Block(
 *  id = "courses_block",
 *  admin_label = @Translation("Courses Block"),
 * )
 */
class CoursesBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $config = $this->getConfiguration();
    //kint($config);
    if (!empty($config['semester'])) {
      $semester = $config['semester'];
    }
    else {
      $semester = "FA19";
    }
    if (!empty($config['courses_shown'])) {
      $courses_shown = $config['courses_shown'];
    }
    else {
      //1 shows 2, 2 shows 3 etc.
      $courses_shown = 1;
    }

    if (!empty($config['keyword_params'])) {
      $keyword_params = $config['keyword_params'];
    }
    else {
      $keyword_params = "MATH";
    }
    $build = [];
    $build['courses_block']['#markup'] = "";
    $course_count = 0;
    $course_json = as_courses_get_courses_json($semester,$keyword_params);
    if (!empty($course_json)) {
      foreach($course_json as $course_data) {
        if ($course_count <= $courses_shown) {
            $build['courses_block']['#markup'] = $build['courses_block']['#markup'] . as_courses_generate_course_item_markup($course_data,$semester);
          $course_count++;
        }

      }
      $build['courses_block']['#markup'] = $build['courses_block']['#markup'] . "<div><a href='https://classes.cornell.edu/browse/roster/" . $semester . "/subject/" . $keyword_params . "'>Full Listing of " . $keyword_params . " Courses for " . $semester . " Semester</a></div>";
    } // There were no courses
    else {
      $build['courses_block']['#markup'] = "<main>
                <h1>Courses</h1>
                <p>There are no courses to list.</p>
                </main>";
    }

    //$build['courses_block']['#markup'] = 'Implement CoursesBlock.';

    return $build;
  }

}
