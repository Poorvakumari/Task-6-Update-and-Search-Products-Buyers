<?php
function getPagination($total_records, $records_per_page, $current_page) {
    $total_pages = ceil($total_records / $records_per_page);
    $pagination = '';

    if ($total_pages > 1) {
        $pagination .= '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';

        // Previous button
        if ($current_page > 1) {
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . ($current_page - 1) . '">Previous</a></li>';
        }

        // Page numbers
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $current_page) ? 'active' : '';
            $pagination .= '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        }

        // Next button
        if ($current_page < $total_pages) {
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . ($current_page + 1) . '">Next</a></li>';
        }

        $pagination .= '</ul></nav>';
    }

    return $pagination;
}

function getSearchParams($params) {
    $search_params = '';
    foreach ($params as $key => $value) {
        if ($key != 'page' && !empty($value)) {
            $search_params .= '&' . $key . '=' . urlencode($value);
        }
    }
    return $search_params;
}
?>
