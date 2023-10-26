{{-- tabes --}}

<div class="card card-transparent ">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
      <li class="nav-item">
        <a href="#" class="active" data-toggle="tab" data-target="#slide1"><span>Home</span></a>
      </li>
      <li class="nav-item">
        <a href="#" data-toggle="tab" data-target="#slide2"><span>Profile</span></a>
      </li>
      <li class="nav-item">
        <a href="#" data-toggle="tab" data-target="#slide3"><span>Messages</span></a>
      </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane slide-left active" id="slide1">
        <div class="row column-seperation">
          <div class="col-lg-6">
            <h3>
                <span class="semi-bold">Sometimes</span> Small things in life means the most
            </h3>
          </div>
          <div class="col-lg-6">
            <h3 class="semi-bold">great tabs</h3>
            <p>Native boostrap tabs customized to Pages look and feel, simply changing class name you can change color as well as its animations</p>
          </div>
        </div>
      </div>
      <div class="tab-pane slide-left" id="slide2">
        <div class="row">
          <div class="col-lg-12">
            <h3>“ Nothing is
              <span class="semi-bold">impossible</span>, the word itself says 'I'm
              <span class="semi-bold">possible</span>'! ”
            </h3>
            <p>A style represents visual customizations on top of a layout. By editing a style, you can use Squarespace's visual interface to customize your...</p>
            <br>
            <p class="pull-right">
              <button type="button" class="btn btn-default btn-cons">White</button>
              <button type="button" class="btn btn-success btn-cons">Success</button>
            </p>
          </div>
        </div>
      </div>
      <div class="tab-pane slide-left" id="slide3">
        <div class="row">
          <div class="col-lg-12">
            <h3>Follow us &amp; get updated!</h3>
            <p>Instantly connect to what's most important to you. Follow your friends, experts, favorite celebrities, and breaking news.</p>
            <br>
          </div>
        </div>
      </div>
    </div>
  </div>



  {{-- side tabes --}}


  <div class="card card-transparent flex-row-reverse">
    <ul class="nav nav-tabs nav-tabs-simple nav-tabs-right bg-white" id="tab-4" role="tablist">
      <li class="nav-item">
        <a href="#" data-toggle="tab" role="tab" data-target="#tab4hellowWorld">One</a>
      </li>
      <li class="nav-item">
        <a href="#" data-toggle="tab" role="tab" data-target="#tab4FollowUs">Two</a>
      </li>
      <li class="nav-item">
        <a href="#" class="active" data-toggle="tab" role="tab" data-target="#tab4Inspire">Three</a>
      </li>
    </ul>
    <div class="tab-content bg-white">
      <div class="tab-pane" id="tab4hellowWorld">
        <div class="row column-seperation">
          <div class="col-lg-6">
            <h3>
                <span class="semi-bold">Sometimes</span> Small things in life means the most
            </h3>
          </div>
          <div class="col-lg-6">
            <h3 class="semi-bold">great tabs</h3>
            <p>Native boostrap tabs customized to Pages look and feel, simply changing class name you can change color as well as its animations</p>
          </div>
        </div>
      </div>
      <div class="tab-pane " id="tab4FollowUs">
        <div class="row">
          <div class="col-lg-12">
            <h3>“ Nothing is
                                  <span class="semi-bold">impossible</span>, the word itself says 'I'm
                                  <span class="semi-bold">possible</span>'! ”</h3>
            <p>A style represents visual customizations on top of a layout. By editing a style, you can use Squarespace's visual interface to customize your...</p>
            <br>
            <p class="pull-right">
              <button type="button" class="btn btn-default btn-cons">White</button>
              <button type="button" class="btn btn-success btn-cons">Success</button>
            </p>
          </div>
        </div>
      </div>
      <div class="tab-pane active" id="tab4Inspire">
        <div class="row">
          <div class="col-lg-12">
            <h3>Follow us &amp; get updated!</h3>
            <p>Instantly connect to what's most important to you. Follow your friends, experts, favorite celebrities, and breaking news.</p>
            <br>
          </div>
        </div>
      </div>
    </div>
  </div>
  

  {{-- form --}}

  <div class="card card-default">
    <div class="card-header ">
      <div class="card-title">
        Option #one
      </div>
    </div>
    <div class="card-body">
      <h5>
              Pages default style
          </h5>
      <form class="" role="form">
        <div class="form-group form-group-default required">
          <label>Project</label>
          <input type="email" class="form-control" required="">
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group form-group-default required">
              <label>First name</label>
              <input type="text" class="form-control" required="">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group form-group-default">
              <label>Last name</label>
              <input type="text" class="form-control">
            </div>
          </div>
        </div>
        <div class="form-group form-group-default required">
          <label>Password</label>
          <input type="password" class="form-control" required="">
        </div>
        <div class="form-group form-group-default required">
          <label>Placeholder</label>
          <input type="email" class="form-control" placeholder="ex: some@example.com" required="">
        </div>
        <div class="form-group form-group-default disabled">
          <label>Disabled</label>
          <input type="email" class="form-control" value="You can put anything here" disabled="">
        </div>
      </form>
    </div>
  </div>

  {{-- radio buttons --}}

  <div class="col-lg-4">
    <h5>Color
            <span class="semi-bold">Options</span>
        </h5>
    <p>Pure CSS radio button with a cool animation. These are available in all primary colors in bootstrap
    </p>
    <br>
    <div class="radio radio-success">
      <input type="radio" value="yes" name="optionyes" id="yes">
      <label for="yes">Agree</label>
      <input type="radio" checked="checked" value="no" name="optionyes" id="no">
      <label for="no">Disagree</label>
    </div>
  </div>

  {{-- checkbox --}}

  <div class="col-lg-4">
    <h5>Color
            <span class="semi-bold">Options</span>
        </h5>
    <p>Our very own image-less pure CSS and retina compatible check box. These check boxes are customized and aviable in all boostrap color classes</p>
    <br>
    <div class="checkbox ">
      <input type="checkbox" value="1" id="checkbox1">
      <label for="checkbox1">Keep Me Signed in</label>
    </div>
    <div class="checkbox check-success  ">
      <input type="checkbox" checked="checked" value="1" id="checkbox2">
      <label for="checkbox2">I agree</label>
    </div>
    <div class="checkbox check-primary">
      <input type="checkbox" value="1" id="checkbox3">
      <label for="checkbox3">Mark</label>
    </div>
    <div class="checkbox check-info">
      <input type="checkbox" value="1" id="checkbox4">
      <label for="checkbox4">Steve Jobs</label>
    </div>
    <div class="checkbox check-warning">
      <input type="checkbox" checked="checked" value="1" id="checkbox5">
      <label for="checkbox5">Action</label>
    </div>
    <div class="checkbox check-danger">
      <input type="checkbox" checked="checked" value="1" id="checkbox6">
      <label for="checkbox6">Mark as read</label>
    </div>
  </div>

  {{-- toggle buttons --}}
  
  <div class="row">
    <div class="col-6">
      <input type="checkbox" data-init-plugin="switchery" checked="checked" disabled="disabled" />
      <input type="checkbox" data-init-plugin="switchery" checked="checked" />
    </div>
    <div class="col-6">
      <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="primary" checked="checked" />
      <input type="checkbox" data-init-plugin="switchery" data-size="large" data-color="primary" checked="checked" />
    </div>
  </div>

  {{-- multiselect --}}

  <form class="m-t-10" role="form">
    <div class="form-group form-group-default form-group-default-select2">
      <label>Project</label>
      <select class=" full-width" data-init-plugin="select2" multiple>
        <option value="Jim">Jim</option>
        <option value="John">John</option>
        <option value="Lucy">Lucy</option>
      </select>
    </div>
  </form>

