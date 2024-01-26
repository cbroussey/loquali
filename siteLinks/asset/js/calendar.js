const prevYear = document.getElementById("prevYear");
const nextYear = document.getElementById("nextYear");
const submit = document.getElementById("valideyy");
const prevOrNext = document.getElementById("prevOrNext");

prevYear.addEventListener('click', function() {
    prevOrNext.value = "prev";
});

nextYear.addEventListener('click', function() {
    prevOrNext.value = "next";
});

submit.addEventListener('click', function() {
    prevOrNext.value = "submit";
})
