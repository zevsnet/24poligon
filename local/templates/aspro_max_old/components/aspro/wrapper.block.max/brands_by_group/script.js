document.documentElement.addEventListener("click", function (e) {
  let $node;
  if (($node = e.target.closest(".filter-link"))) {
    if ($node.classList.contains("active")) return;

    document.querySelectorAll(".filter-link").forEach(function ($link) {
      $link.classList.remove("active");
      if ($link.dataset["letter"] === $node.dataset["letter"]) {
        $link.classList.add("active");
      }
    });
    $node.classList.add("active");

    const $brandsWrapper = document.querySelector(".js-brands");
    const letter = $node.dataset["letter"];
    $brandsWrapper.dataset["letter"] = letter;

    const loc = new URL(location.href);
    if (loc.searchParams.has("letter")) {
      loc.searchParams.delete("letter");
    }
    if (letter) {
      loc.searchParams.append("letter", letter);
    }

    if (window.history && typeof window.history === "object") {
      window.history.replaceState(null, "", loc);
    }

    requestBrand($brandsWrapper, $brandLoader, function () {
      const $filterWrapper = document.querySelector(".filter-letters");
      const filterWrapperPos = BX.pos($filterWrapper)
      const offsetDiff = 80;
      const positionTop = filterWrapperPos.top - offsetDiff;
      if (window.scrollY > positionTop) {
        window.scrollTo(0, positionTop);
      }
    });
  }
});
