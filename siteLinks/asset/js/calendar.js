const prevYear = document.getElementById("prevYear");
const nextYear = document.getElementById("nextYear");
const prevOrNext = document.getElementById("prevOrNext");

prevYear.addEventListener('click', function() {
    prevOrNext.value = "prev";
});

nextYear.addEventListener('click', function() {
    prevOrNext.value = "next";
});
