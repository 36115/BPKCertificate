<?php
    // ป้องกันการเข้าแบบไม่ถูกต้อง
    if (!defined('SECURE_ACCESS')) {
        header("location: ../");
    }
?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.bootstrap5.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/5.0.1/css/fixedColumns.dataTables.css"/>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.5/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/5.0.1/js/dataTables.fixedColumns.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/5.0.1/js/fixedColumns.dataTables.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>

<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 800px;
        margin: 0 auto;
    }
</style>

<script>
    // Search Bar Theme
    document.addEventListener('DOMContentLoaded', function() {

        function applyModifications() {
            // Handle all dt-search-* inputs
            document.querySelectorAll('[id^="dt-search-"]').forEach(function(searchInput) {
                var labelElement = document.querySelector(`label[for="${searchInput.id}"]`);
                if (labelElement) labelElement.remove();
                
                searchInput.classList.add('rounded-pill', 'py-2');
                searchInput.placeholder = 'ค้นหาจากรายการ';
                
                // Find the closest parent with class 'dt-search' and modify it
                var dtSearchParent = searchInput.closest('.dt-search');
                if (dtSearchParent) {
                    dtSearchParent.classList.remove('dt-search');
                    dtSearchParent.classList.add('table-search');
                }
            });

            var rowElement = document.querySelector('.row.mt-2.justify-content-between');
            if (rowElement) {
                rowElement.classList.remove('row', 'mt-2', 'justify-content-between');
                rowElement.classList.add('mt-2');
            }

            var rowElement2 = document.querySelector('.d-md-flex.justify-content-between.align-items-center.dt-layout-end.col-md-auto.ms-auto');
            if (rowElement2) {
                rowElement2.classList.remove('d-md-flex', 'justify-content-between', 'align-items-center', 'dt-layout-end', 'col-md-auto', 'ms-auto');
                rowElement2.classList.add('p-3', 'pt-3', 'pb-5');
            }

            // Remove td.dt-type-numeric
            document.querySelectorAll('td.dt-type-numeric').forEach(function(td) {
                td.classList.remove('dt-type-numeric');
            });

            // Select all <tr> elements with the specified attributes and add 'modal-table' class
            document.querySelectorAll('tr[data-dt-row][data-dt-column]').forEach(row => {
                row.classList.add('modal-table');
            });
        }

        // Set up MutationObserver
        var observer = new MutationObserver(function(mutationsList) {
            for (let mutation of mutationsList) {
                if (mutation.type === 'childList') {
                    applyModifications();
                    if (typeof(userSearch) === "function") {
                        userSearch();
                    }
                }
            }
        });

        // Start observing the document with the configured parameters
        observer.observe(document.body, { childList: true, subtree: true });

        // Initial application
        applyModifications();
    });
</script>