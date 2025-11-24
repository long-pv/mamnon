<?php
// Template name: Demo
get_header();
?>
<div class="lv_container">
    <!-- ===== Modal ===== -->
    <h2>🔹 Modal</h2>
    <button class="open-modal" data-target="#myModal1">Mở Modal 1</button>
    <button class="open-modal" data-target="#myModal2">Mở Modal 2</button>

    <div class="modal" id="myModal1">
        <div class="modal-content">
            <button type="button" class="modal-close" aria-label="Close">&times;</button>
            <h3>Modal 1</h3>
            <p>Đây là nội dung modal số 1.</p>
        </div>
    </div>

    <div class="modal" id="myModal2">
        <div class="modal-content">
            <button type="button" class="modal-close" aria-label="Close">&times;</button>
            <h3>Modal 2</h3>
            <p>Đây là nội dung modal số 2.</p>
        </div>
    </div>

    <!-- ===== Tabs ===== -->
    <h2>🔹 Tabs</h2>
    <div class="tabs">
        <div class="tab-links">
            <a href="#tab1" class="active">Tab 1</a>
            <a href="#tab2">Tab 2</a>
        </div>
        <div class="tab-content active" id="tab1">Nội dung Tab 1</div>
        <div class="tab-content" id="tab2">Nội dung Tab 2</div>
    </div>

    <div class="tabs">
        <div class="tab-links">
            <a href="#tab3" class="active">Tab 3</a>
            <a href="#tab4">Tab 4</a>
        </div>
        <div class="tab-content active" id="tab3">Nội dung Tab 3</div>
        <div class="tab-content" id="tab4">Nội dung Tab 4</div>
    </div>

    <!-- ===== Accordion ===== -->
    <h2>🔹 Accordion</h2>
    <div class="accordion">
        <div class="accordion-item">
            <div class="accordion-header">Phần 1</div>
            <div class="accordion-body">Nội dung phần 1</div>
        </div>
        <div class="accordion-item">
            <div class="accordion-header">Phần 2</div>
            <div class="accordion-body">Nội dung phần 2</div>
        </div>
    </div>

    <div class="accordion">
        <div class="accordion-item">
            <div class="accordion-header">Phần A</div>
            <div class="accordion-body">Nội dung phần A</div>
        </div>
        <div class="accordion-item">
            <div class="accordion-header">Phần B</div>
            <div class="accordion-body">Nội dung phần B</div>
        </div>
    </div>
</div>

<?php
get_footer();
?>