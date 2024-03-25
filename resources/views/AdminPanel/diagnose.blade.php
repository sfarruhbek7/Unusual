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
        const URL = "https://teachablemachine.withgoogle.com/models/52_FjHFgW/";

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
            {subject: "", text: ""},
            {subject: "Gladiolus", text: "" + "Gladioli grow from round, symmetrical corms[7] (similar to crocuses) that are enveloped in several layers of brownish, fibrous tunics.[8] Their stems are generally unbranched, producing 1 to 9 narrow, sword-shaped, longitudinal grooved leaves, enclosed in a sheath.[2] The lowest leaf is shortened to a cataphyll. The leaf blades can be plane or cruciform in cross section. The flowers of unmodified wild species vary from very small to perhaps 40 mm across, and inflorescences bearing anything from one to several flowers. The spectacular giant flower spikes in commerce are the products of centuries of hybridisation and selection. The flower spikes are large and one-sided, with secund, bisexual flowers, each subtended by 2 leathery, green bracts. The sepals and the petals are almost identical in appearance, and are termed tepals. They are united at their base into a tube-shaped structure. The dorsal tepal is the largest, arching over the three stamens. The outer three tepals are narrower. The perianth is funnel-shaped, with the stamens attached to its base. The style has three filiform, spoon-shaped branches, each expanding towards the apex.[9] The ovary is 3-locular with oblong or globose capsules,[9] containing many, winged brown, longitudinally dehiscent seeds"},
            {subject: "Calendula", text: "Calendula was not a major medicinal herb but it was used in historic times for headaches, red eye, fever and toothaches. As late as the 17th century Nicholas Culpeper claimed Calendula benefited the heart, but it was not considered an especially efficacious medicine.[6] In historic times Calendula was more often used for magical purposes than medicinal ones. One 16th-century potion containing Calendula claimed to reveal fairies. An unmarried woman with two suitors would take a blend of powdered Calendula, marjoram, wormwood and thyme simmered in honey and white wine used as an ointment in a ritual to reveal her true match"},
            {subject: "", text: ""}
        ]
        // run the webcam image through the image model
        async function predict() {

            // predict can take in an image, video or canvas html element
            const prediction = await model.predict(webcam.canvas);
            for (let i = 0; i < maxPredictions; i++) {
                const classPrediction = prediction[i].className ;
                if(prediction[i].probability.toFixed(2)>0.99) {
                    // labelContainer.childNodes[i].innerHTML = classPrediction;
                    document.getElementById('title').innerHTML=a[i]['subject'];
                    document.getElementById('text').innerHTML=a[i]['text'];
                }
            }
        }
        init();
    </script>





@endsection
