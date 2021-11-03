<section id="preview">
  <div class="container">
    <header class="section-header wow fadeInUp">
      <h3>Preview</h3>
      <p>
        Watch the tutorial and you will learn how to use the Cabuyao Mobile App.
      </p>
    </header>

    <div class="basic-1">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="image-container">
              <div class="video-wrapper text-center">
                <img id="background-image" class="img-fluid" src="{{ asset('assets/new-template/images/video-frame.jpg') }}" alt="alternative"/>
                <span id="play-button" class="video-play-button" onclick="playPause()">
                  <span></span>
                </span><video id="tutorial-video" style="outline: none; display: none;" width="100%" src="{{ asset('assets/new-template/video/tutorial.mp4') }}" autoplay controls></video>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>	
  
  </div>
</section>