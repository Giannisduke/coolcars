<div class="modal fade bd-example-modal-lg" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header formheadnopad">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×</button>
                      <!-- Nav tabs -->
                    <ul class="nav nav-tabs nomargin" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" href="#profile" role="tab" data-toggle="tab">Register</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#buzz" role="tab" data-toggle="tab">login</a>
                      </li>

                    </ul>
            </div>
            <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">

                    <!-- Tab panes -->
                    <div class="tab-content">
                      <div role="tabpanel" class="tab-pane fade in active" id="profile">

                        <?php gravity_form( 'Register 2', false, false, false, '', false ); ?></div>
                      <div role="tabpanel" class="tab-pane fade" id="buzz">

                        <div class="alert alert-info fade in" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                          Already have an account? <strong>Login</strong>
                        </div>

                        <?php dynamic_sidebar('sidebar-secondary'); ?></div>

                    </div>



            </div>
        </div>
    </div>
</div>
