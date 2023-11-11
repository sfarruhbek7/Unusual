@extends('layouts.admin')
@section('content')



    <button type="button" style="display: none" onclick="init()"></button>

    <div class="row mt-4">
        <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card z-index-2 h-100">
                <div class="card-header pb-0 pt-3 bg-transparent">
                    <h6 id="title" class="text-capitalize"></h6>
                    <p id="text" class="text-sm mb-0">
                    </p>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card card-carousel overflow-hidden h-100 p-0">
                <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
                    <div class="carousel-inner border-radius-lg h-100">

                        <div id="webcam-container" style="width: 500px"></div>
                        <div id="label-container"></div>
                        {{--                <div class="carousel-item h-100 active" style="background-image: url('AdminPanel/assets/img/carousel-1.jpg');--}}
                        {{--background-size: cover;">--}}
                        {{--                  <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">--}}
                        {{--                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">--}}
                        {{--                      <i class="ni ni-camera-compact text-dark opacity-10"></i>--}}
                        {{--                    </div>--}}
                        {{--                    <h5 class="text-white mb-1">Get started with Argon</h5>--}}
                        {{--                    <p>There’s nothing I really wanted to do in life that I wasn’t able to get good at.</p>--}}
                        {{--                  </div>--}}
                    </div>
                    <div class="carousel-item h-100" style="background-image: url('AdminPanel/assets/img/carousel-2.jpg');
      background-size: cover;">
                        <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                            <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                <i class="ni ni-bulb-61 text-dark opacity-10"></i>
                            </div>
                            <h5 class="text-white mb-1">Faster way to create web pages</h5>
                            <p>That’s my skill. I’m not really specifically talented at anything except for the ability to learn.</p>
                        </div>
                    </div>
                    <div class="carousel-item h-100" style="background-image: url('AdminPanel/assets/img/carousel-3.jpg');
      background-size: cover;">
                        <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                            <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                <i class="ni ni-trophy text-dark opacity-10"></i>
                            </div>
                            <h5 class="text-white mb-1">Share with us your design tips!</h5>
                            <p>Don’t be afraid to be wrong because you can’t learn anything from a compliment.</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@latest/dist/teachablemachine-image.min.js"></script>
    <script type="text/javascript">
        // More API functions here:
        // https://github.com/googlecreativelab/teachablemachine-community/tree/master/libraries/image

        // the link to your model provided by Teachable Machine export panel
        const URL = "https://teachablemachine.withgoogle.com/models/Mb_3ibrPl/";

        let model, webcam, labelContainer, maxPredictions;

        // Load the image model and setup the webcam
        async function init() {
            const modelURL = URL + "model.json";
            const metadataURL = URL + "metadata.json";

            // load the model and metadata
            // Refer to tmImage.loadFromFiles() in the API to support files from a file picker
            // or files from your local hard drive
            // Note: the pose library adds "tmImage" object to your window (window.tmImage)
            model = await tmImage.load(modelURL, metadataURL);
            maxPredictions = model.getTotalClasses();

            // Convenience function to setup a webcam
            const flip = true; // whether to flip the webcam
            webcam = new tmImage.Webcam(200, 200, flip); // width, height, flip
            await webcam.setup(); // request access to the webcam
            await webcam.play();
            window.requestAnimationFrame(loop);

            // append elements to the DOM
            document.getElementById("webcam-container").appendChild(webcam.canvas);
            labelContainer = document.getElementById("label-container");
            for (let i = 0; i < maxPredictions; i++) { // and class labels
                labelContainer.appendChild(document.createElement("div"));
            }
        }

        async function loop() {
            webcam.update(); // update the webcam frame
            await predict();
            window.requestAnimationFrame(loop);
        }
        let a=[
            {subject: "Lemon", text: "The lemon (Citrus × limon) is a species of small evergreen tree in the flowering plant family Rutaceae, native to Asia, primarily Northeast India (Assam), Northern Myanmar, or China.[2]. The tree's ellipsoidal yellow fruit is used for culinary and non-culinary purposes throughout the world, primarily for its juice, which has both culinary and cleaning uses.[2] The pulp and rind are also used in cooking and baking. The juice of the lemon is about 5-6% citric acid,[citation needed] with a pH of around 2.2,[3] giving it a sour taste. The distinctive sour taste of lemon juice, derived from the citric acid, makes it a key ingredient in drinks and foods[4] such as lemonade and lemon meringue pie"},
            {subject: "Magnolia", text: "Magnolia is an ancient genus that appeared before bees evolved,[when?] they are theorized to have evolved to encourage pollination by beetles instead.[2] Fossilized specimens of M. acuminata have been found dating to 20 million years ago, and fossils of plants identifiably belonging to the Magnoliaceae date to 95 million years ago.[3] Another aspect of Magnolia considered to represent an ancestral state is that the flower bud is enclosed in a bract rather than in sepals; the perianth parts are undifferentiated and called tepals rather than distinct sepals and petals. Magnolia shares the tepal characteristic with several other flowering plants near the base of the flowering plant lineage, such as Amborella and Nymphaea (as well as with many more recently derived plants, such as Lilium)."}
        ]
        // run the webcam image through the image model
        async function predict() {

            // predict can take in an image, video or canvas html element
            const prediction = await model.predict(webcam.canvas);
            for (let i = 0; i < maxPredictions; i++) {
                const classPrediction = prediction[i].className ;
                if(prediction[i].probability.toFixed(2)>0.99) {
                    // labelContainer.childNodes[i].innerHTML = classPrediction;
                    document.getElementById('title').innerHTML=a[i-1]['subject'];
                    document.getElementById('text').innerHTML=a[i-1]['text'];
                }
            }
        }
        init();
    </script>





@endsection
