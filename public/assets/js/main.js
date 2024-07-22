$(document).ready(function () {
  new Swiper(".swiper-promotion", {
    loop: true,
    slidesPerView: 1,
    centeredSlides: false,
    spaceBetween: 12,
    speed: 700,
    autoplay: {
      delay: 4700,
    },
    dots: true,
    pagination: {
      el: ".swiper-pagination",
      type: "bullets",
      clickable: "true",
    },
  });
  new Swiper(".swiper-recently", {
    loop: false,
    slidesPerView: 2.3,
    centeredSlides: false,
    spaceBetween: 12,
    speed: 700,
    delay: 4700,
  });
  new Swiper(".slider-regular", {
    direction: "horizontal",
    loop: false,
    slidesPerView: "auto",
    spaceBetween: 15,
  });
  new Swiper(".swiper-onboard", {
    loop: false,
    slidesPerView: 1,
    centeredSlides: false,
    spaceBetween: 32,
    speed: 700,
    delay: 4700,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
  });
});

$(document).ready(function () {
  if (!$("body").hasClass("bottom-nav")) {
    var navHeight = $(".bottom-nav").outerHeight();
    $(".bottom-spacer").css("height", navHeight + "px");
  }

  $(".song-add").click(function () {
    alert("Success add this song to playlist");
    $(this).replaceWith(
      '<span class="icon-check-circle-fill song-added"></span>'
    );
  });

  // player config start

  const next = document.querySelector("#next");
  const play = document.querySelector("#play");
  const prev = document.querySelector("#prev");
  const progressBar = document.querySelector("#progress-bar");
  const musicTitle = document.querySelector(".music-name");
  const musicAlbum = document.querySelector(".music-album");
  const musicCard = document.querySelector(".music-card");
  const musicArtist = document.querySelector(".music-artist");
  const musicCover = document.querySelector(".music-image");
  const musicCurrentTime = document.querySelector("#musicTimeCurrent");
  const musicDurationTime = document.querySelector("#musicTimeDuration");
  const backgroundImage = document.querySelector("#backgroundImage");
  const music = document.querySelector("audio");
  const progressZone = document.querySelector(".music-progress");

  let isPlaying = false;
  // default select first music
  let selectedMusic = 1;

  play.addEventListener("click", () => {
    isPlaying ? pauseMusic() : playMusic();
  });

  const playList = [
    {
      artist: "Post Malone",
      cover:
        "https://yildirimzlm.s3.us-east-2.amazonaws.com/post-malone-2.jpeg",
      musicName: "Rockstar ft. 21 Savage",
      musicPath: `https://yildirimzlm.s3.us-east-2.amazonaws.com/Post+Malone+-+rockstar+ft.+21+Savage+(Official+Audio).mp3`,
      musicAlbum: "Stoney",
    },
    {
      artist: "Unlike Pluto",
      cover: "https://yildirimzlm.s3.us-east-2.amazonaws.com/unlike-pluto.jpeg",
      musicName: "No Scrubs ft. Joanna Jones",
      musicPath: `https://yildirimzlm.s3.us-east-2.amazonaws.com/Unlike+Pluto+-+No+Scrubs+ft.+Joanna+Jones+(Cover).mp3`,
      musicAlbum: "No Scrubs",
    },
    {
      artist: "Post Malone",
      cover: "https://yildirimzlm.s3.us-east-2.amazonaws.com/circles.jpeg",
      musicName: "Circles",
      musicPath: `https://yildirimzlm.s3.us-east-2.amazonaws.com/Post+Malone+-+Circles+(Lyrics).mp3`,
      musicAlbum: "Hollywood's Bleeding",
    },
  ];

  const playMusic = () => {
    music.play();
    document
      .querySelector(".music-control-play")
      .classList.replace("icon-player-play", "icon-player-pause");
    isPlaying = true;
    fadeInCover();
    musicCard.classList.add("middle-weight");
    setTimeout(() => {
      musicCard.classList.remove("middle-weight");
    }, 200);
  };

  const pauseMusic = () => {
    music.pause();
    document
      .querySelector(".music-control-play")
      .classList.replace("icon-player-pause", "icon-player-play");
    isPlaying = false;
    fadeInCover();
    musicCard.classList.add("middle-weight");
    setTimeout(() => {
      musicCard.classList.remove("middle-weight");
    }, 200);
  };

  const nextMusic = () => {
    selectedMusic = (selectedMusic + 1) % playList.length;
    loadMusic(playList[selectedMusic]);
    music.duration = 0;
    if (isPlaying) {
      music.play();
    }
    // musicCard.classList.add('right-weight');
    progressBar.style.width = `0%`;
    // setTimeout(() => {
    //     musicCard.classList.remove('right-weight');
    // }, 200)
  };

  const prevMusic = () => {
    selectedMusic = (selectedMusic - 1 + playList.length) % playList.length;
    loadMusic(playList[selectedMusic]);
    if (isPlaying) {
      music.play();
    }
    // musicCard.classList.add('left-weight');
    progressBar.style.width = `0%`;
    // setTimeout(() => {
    //     musicCard.classList.remove('left-weight');
    // }, 200)
  };

  const loadMusic = (playList) => {
    musicArtist.textContent = playList.artist;
    musicTitle.textContent = playList.musicName;
    musicAlbum.textContent = playList.musicAlbum;
    music.src = playList.musicPath;
    musicCover.src = `${playList.cover}`;
    backgroundImage.src = `${playList.cover}`;
    backgroundImage.animate(
      [
        {
          opacity: 0,
        },
        {
          opacity: 1,
        },
      ],
      {
        duration: 400,
      }
    );
    fadeInCover();
  };

  const fadeInCover = () => {
    musicCover.classList.add("animate");
    setTimeout(() => {
      musicCover.classList.remove("animate");
    }, 300);
  };

  // Update progress
  const updateProgress = (e) => {
    const { duration, currentTime } = e.srcElement;
    const progressPercent = (currentTime / duration) * 100;
    progressBar.style.width = `${progressPercent}%`;

    if (progressPercent == 100) {
      setTimeout(() => {
        nextMusic();
      }, 500);
    }
  };

  // Set progress
  function setProgress(e) {
    const width = this.clientWidth;
    const setPoint = e.offsetX;
    const duration = music.duration;
    music.currentTime = (setPoint / width) * duration;
  }

  // Set time area
  const setMusicTime = (e) => {
    const { duration, currentTime } = e.srcElement;
    calcSongTime(duration, musicDurationTime);
    calcSongTime(currentTime, musicCurrentTime);
  };

  const calcSongTime = (time, selectTime) => {
    time = Number(time);
    const m = Math.floor((time % 3600) / 60);
    const s = Math.floor((time % 3600) % 60);
    if (m < 10) {
      minute = "0" + m;
    } else minute = m;
    if (s < 10) {
      second = "0" + s;
    } else second = s;

    return (selectTime.textContent = `${minute}:${second}`);
  };

  next.addEventListener("click", nextMusic);
  prev.addEventListener("click", prevMusic);
  music.addEventListener("timeupdate", updateProgress);
  music.addEventListener("timeupdate", setMusicTime);
  progressZone.addEventListener("click", setProgress);

  function cardAnimate(e) {
    this.querySelectorAll(".music-card").forEach(function (boxMove) {
      const x = -(window.innerWidth / 3 - e.pageX) / 90;
      const y = (window.innerHeight / 3 - e.pageY) / 30;
      boxMove.style.transform = "rotateY(" + x + "deg) rotateX(" + y + "deg)";
    });
  }

  // player config end
});

// Gunakan jQuery untuk menangani peristiwa klik pada checkbox
$(document).ready(function () {
  // Inisialisasi totalAmount
  var totalRegular = 0;

  // Fungsi untuk menampilkan atau menyembunyikan div berdasarkan nilai totalAmount dengan animasi fade
  function toggleResultContainer() {
    if (totalRegular === 0) {
      $("#resultPrice").fadeOut();
    } else {
      $("#resultPrice").fadeIn();
    }
  }

  // Ketika checkbox diubah
  var selectedRadio = $(".btn-check:checked");
  $(".btn-check").change(function () {
    // Jika checkbox dicentang, tambahkan 20.000 ke totalRegular
    var selectedRadio = $(".btn-check:checked");
    totalRegular = parseInt(selectedRadio.val(), 10);

    var formattedtotalRegular =
      "₭" + totalRegular.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    // Update nilai
    $("#totalRegular").text(formattedtotalRegular);

    toggleResultContainer();
  });
});
