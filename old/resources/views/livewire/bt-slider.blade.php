<div class="el">
    <div id="{{$slider_id}}" class="carousel slide el" data-bs-ride="carousel">
        <div class="carousel-inner el" style="height: 300px;">
            <div class="carousel-item active el">
                <img src="{{$slide1}}" class="d-block w-100 el" alt="...">
            </div>
            <div class="carousel-item el">
                <img src="{{$slide2}}" class="d-block w-100 el" alt="...">
            </div>
            <div class="carousel-item el">
                <img src="{{$slide3}}" class="d-block w-100 el" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev el" type="button" data-bs-target="#{{$slider_id}}" data-bs-slide="prev">
          <span class="carousel-control-prev-icon el" aria-hidden="true"></span>
          <span class="visually-hidden el">Previous</span>
        </button>
        <button class="carousel-control-next el" type="button" data-bs-target="#{{$slider_id}}" data-bs-slide="next">
          <span class="carousel-control-next-icon el" aria-hidden="true"></span>
          <span class="visually-hidden el">Next</span>
        </button>
    </div>
</div>