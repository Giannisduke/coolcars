<div class="pretty outline-success plain">
  <input type="checkbox"/>
  <label><i class="mdi mdi-check"></i> Oh, yes!</label>
</div>
<div class="pretty outline-danger plain">
  <input type="checkbox"/>
  <label><i class="mdi mdi-close"></i> OMG, no!</label>
</div>


<div class="container">
  <div class="row">
    <div class="col-lg-6">
    left

    </div>

    <div class="col-lg-6">
    <div class="row">
      <div class="col-lg-4 test text-center">
<input type="radio" name='choice-animals' checked><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i> <span>  Male</span>
      <p><label for="choice-animals">I like cats more 1</label></p>
      <div class="reveal-if-active">

      </div>
      </div>


      <div class="col-lg-4 test text-center">
      <input type="radio" name="choice-animals" id="choice-animals-cats2" value="Port" >
      <p><label for="choice-animals">I like cats more 2</label></p>
      <div class="reveal-if-active">

      </div>
      </div>



      <div class="col-lg-4 test text-center">
      <input type="radio" name="choice-animals" id="choice-animals-cats2" value="hotel" >
      <p><label for="choice-animals">I like cats more 3</label></p>
      <div class="reveal-if-active">

        <textarea name="pick-up"  class="input-text form-control custom-textarea" data-require-pair="#pick-up-hotel" id="order_comments" placeholder="placeholder2" rows="4" cols="5"></textarea>
      </div>
      </div>



</div>

</div>
</div>
</div>







<form>
  <div class="test">
    <label class="radio-inline custom-radio custom-control">
      <input id="radio1" type="radio" name="optradio" class="custom-control-input" value="Port">
      <span class="custom-control-indicator"></span>
      <span class="custom-control-description">Boots</span>
    </label>
</div>


<div class="test">
    <label class="radio-inline custom-radio custom-control">
      <input id="radio2" type="radio" name="optradio" class="custom-control-input" value="Porwffft">
      <span class="custom-control-indicator"></span>
      <span class="custom-control-description">Boots2</span>
    </label>
</div>

    <label class="radio-inline">
      <input type="radio" name="optradio">Option 3
    </label>
  </form>




<form data-toggle="validator" role="form">
  <fieldset>
  <div class="form-group row">
    <label for="inputName" class="control-label">Name</label>
    <input type="text" class="form-control" id="inputName" placeholder="Cina Saffary" required>
  </div>
  <div class="form-group has-feedback">
    <label for="inputTwitter" class="control-label">Twitter</label>
    <div class="input-group">
      <span class="input-group-addon">@</span>
      <input type="text" pattern="^[_A-z0-9]{1,}$" maxlength="15" class="form-control" id="inputTwitter" placeholder="1000hz" required>
    </div>
    </fieldset>
    <fieldset>
        <div class="form-group">
    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
    <div class="help-block with-errors">Hey look, this one has feedback icons!</div>
  </div>
  <div class="form-group">
    <label for="inputEmail" class="control-label">Email</label>
    <input type="email" class="form-control" id="inputEmail" placeholder="Email" data-error="Bruh, that email address is invalid" required>
    <div class="help-block with-errors"></div>
  </div>
  <div class="form-group">
    <label for="inputPassword" class="control-label">Password</label>
    <div class="form-inline row">
      <div class="form-group col-sm-6">
        <input type="password" data-minlength="6" class="form-control" id="inputPassword" placeholder="Password" required>
        <div class="help-block">Minimum of 6 characters</div>
      </div>
      <div class="form-group col-sm-6">
        <input type="password" class="form-control" id="inputPasswordConfirm" data-match="#inputPassword" data-match-error="Whoops, these don't match" placeholder="Confirm" required>
        <div class="help-block with-errors"></div>
      </div>
    </div>
  </div>
    </div>
  </fieldset>
  <div class="form-group">
    <div class="radio">
      <label>
        <input type="radio" name="underwear" required>
        Boxers
      </label>
    </div>
    <div class="radio">
      <label>
        <input type="radio" name="underwear" required>
        Briefs
      </label>
    </div>
  </div>
  <div class="form-group">
    <div class="checkbox">
      <label>
        <input type="checkbox" id="terms" data-error="Before you wreck yourself" required>
        Check yourself
      </label>
      <div class="help-block with-errors"></div>
    </div>
  </div>
  <div class="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>

</form>
