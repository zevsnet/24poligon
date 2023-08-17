if (!window.TableScroller) {
  // function TableScroller(el, config) {
  TableScroller = function (el, config) {
    this.$wrapper = null;
    this.$header = null;

    this.classes = el;
    this.touch = false;

    this.scrollY = 0;

    this.config = {
      showClass: "showing",
      checkFixedHeader: true,
      fixedHeaderID: "headerfixed",
    };

    this.init(config);
  };

  TableScroller.prototype = {
    init: function (config) {
      this.setElements();

      if (!this.$wrapper.classList.contains("scroller")) {
        return;
      }

      this.scrollStartEventName = this.checkTouch() ? "touchstart" : "mouseenter";
      this.scrollEndEventName = this.checkTouch() ? "touchend" : "mouseleave";

      this.debounceScrollHandler = BX.debounce(this._onWindowScroll, 400, this);

      this.appendEar();

      this.mergeConfig(config);

      setTimeout(
        function () {
          this.toggle();
        }.bind(this),
        0
      );
      this.adjustEarOffset(true);

      this.addEvents();
    },
    destroy: function () {
      this.removeEvents();
    },

    setElements: function () {
      this.$wrapper = document.getElementById(this.classes);
      this.$header = document.getElementById(this.classes + "__header");
    },
    mergeConfig: function (config) {
      if (Object.prototype.toString.call(config).slice(8, -1) === "Object") {
        this.config = Object.assign(this.config, config);
      }
    },

    addEvents: function () {
      document.addEventListener("scroll", this.debounceScrollHandler, { passive: true });

      this.$wrapper.addEventListener("scroll", this.toggle.bind(this), { passive: true });
      window.addEventListener("resize", this.toggle.bind(this));

      this.getEarLeft().addEventListener(this.scrollStartEventName, this._onMouseoverLeft.bind(this));
      this.getEarRight().addEventListener(this.scrollStartEventName, this._onMouseoverRight.bind(this));
      this.getEarLeft().addEventListener(this.scrollEndEventName, this.stopScroll.bind(this));
      this.getEarRight().addEventListener(this.scrollEndEventName, this.stopScroll.bind(this));
    },
    removeEvents: function () {
      document.removeEventListener("scroll", this.debounceScrollHandler, { passive: true });
      this.$wrapper.removeEventListener("scroll", this.toggle.bind(this), { passive: true });

      window.removeEventListener("resize", this.toggle.bind(this));

      this.getEarLeft().removeEventListener(this.scrollStartEventName, this._onMouseoverLeft.bind(this));
      this.getEarRight().removeEventListener(this.scrollStartEventName, this._onMouseoverRight.bind(this));
      this.getEarLeft().removeEventListener(this.scrollEndEventName, this.stopScroll.bind(this));
      this.getEarRight().removeEventListener(this.scrollEndEventName, this.stopScroll.bind(this));
    },
    checkTouch: function () {
      if (!this.touch) {
        this.touch = document.documentElement.classList.contains("bx-touch");
      }
      return this.touch;
    },

    _onWindowScroll: function () {
      this.adjustEarOffset();
    },
    hasScroll: function () {
      return this.$header.offsetWidth > this.$wrapper.clientWidth;
    },
    hasScrollLeft: function () {
      return this.$wrapper.scrollLeft > 0;
    },
    hasScrollRight: function () {
      return this.$header.offsetWidth > this.$wrapper.scrollLeft + this.$wrapper.clientWidth;
    },
    stopScroll: function () {
      clearTimeout(this.scrollTimer);
      clearInterval(this.scrollInterval);
    },

    appendEar: function () {
      this.addEar("left");
      this.addEar("right");
    },
    addEar: function (pos) {
      const div = document.createElement("div");
      div.classList.add(this.classes + "__ear");
      div.classList.add(this.classes + "__ear--" + pos);
      this.$wrapper.insertBefore(div, this.$header);
    },
    getEarLeft: function () {
      if (!this.earLeft) {
        this.earLeft = this.$wrapper.querySelector("." + this.classes + "__ear--left");
      }

      return this.earLeft;
    },
    getEarRight: function () {
      if (!this.earRight) {
        this.earRight = this.$wrapper.querySelector("." + this.classes + "__ear--right");
      }

      return this.earRight;
    },
    getFixedHeaderHeight: function () {
      let height = 0;
      if (this.config.checkFixedHeader) {
        let headerFixed = BX.pos(document.getElementById(this.config.fixedHeaderID));
        if (this.scrollY + headerFixed.height > this.bodyPos.top) {
          return headerFixed.height * 2;
        }
        return height;
      }
      return height;
    },
    processFixedHeader: function () {
      if (this.config.checkFixedHeader) {
        let headerFixed = BX.pos(document.getElementById(this.config.fixedHeaderID));

        if (this.scrollY + headerFixed.height > this.bodyPos.top) {
          this.bodyPos.top += headerFixed.height;
        } else {
          this.bodyPos.top = this.bodyPos.topRaw;
        }
      }
    },
    adjustEarOffset: function (prepare) {
      if (this.checkMedia()) {
        return;
      }

      this.scrollY = window.scrollY;

      if (document.documentElement.classList.contains("bx-ie")) {
        this.scrollY = document.documentElement.scrollTop;
      }

      //   if (prepare) {
      this.windowHeight = BX.height(window);
      this.bodyPos = BX.pos(this.$wrapper);
      this.headerPos = BX.pos(this.$header);

      this.bodyPos.top += this.headerPos.height;

      this.headerPos.heightRaw = this.headerPos.height;
      this.bodyPos.topRaw = this.bodyPos.top;
      //   }
      //   this.processFixedHeader();

      let bottomPos = this.scrollY + this.windowHeight - this.bodyPos.top;
      let posTop = this.scrollY - this.bodyPos.top + this.getFixedHeaderHeight();

      if (bottomPos > this.bodyPos.bottom - this.bodyPos.top) {
        bottomPos = this.bodyPos.bottom - this.bodyPos.top;
      }

      if (posTop < this.headerPos.height) {
        posTop = this.headerPos.height;
      } else {
        bottomPos -= posTop;
        bottomPos += this.headerPos.height;
      }

      this.requestAnimationFrame(
        function () {
          if (posTop !== this.lastPosTop) {
            var translate = "translate3d(0px, " + posTop + "px, 0)";
            this.getEarLeft().style.transform = translate;
            this.getEarRight().style.transform = translate;
          }

          if (bottomPos !== this.lastBottomPos) {
            this.getEarLeft().style.height = bottomPos + "px";
            this.getEarRight().style.height = bottomPos + "px";
          }

          this.lastPosTop = posTop;
          this.lastBottomPos = bottomPos;
        }.bind(this)
      );
    },

    requestAnimationFrame: function () {
      var raf =
        window.requestAnimationFrame ||
        window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.msRequestAnimationFrame ||
        window.oRequestAnimationFrame ||
        function (callback) {
          window.setTimeout(callback, 1000 / 60);
        };

      raf.apply(window, arguments);
    },

    showLeftEar: function () {
      this.getEarLeft().classList.add(this.config["showClass"]);
    },
    hideLeftEar: function () {
      this.getEarLeft().classList.remove(this.config["showClass"]);
    },
    showRightEar: function () {
      this.getEarRight().classList.add(this.config["showClass"]);
    },
    hideRightEar: function () {
      this.getEarRight().classList.remove(this.config["showClass"]);
    },

    _onMouseoverLeft: function (event) {
      this.checkTouch() && event.preventDefault();
      this.startScrollByDirection("left");
    },
    _onMouseoverRight: function (event) {
      this.checkTouch() && event.preventDefault();
      this.startScrollByDirection("right");
    },

    startScrollByDirection: function (direction) {
      let container = this.$wrapper;
      let offset = container.scrollLeft;
      let self = this;
      let stepLength = 8;
      let stepTime = 1000 / 60 / 2;

      this.scrollTimer = setTimeout(function () {
        self.scrollInterval = setInterval(function () {
          container.scrollLeft = direction == "right" ? (offset += stepLength) : (offset -= stepLength);
        }, stepTime);
      }, 100);
    },

    checkMedia: function () {
      if (window.matchMedia("(max-width: 991px)").matches) {
        return true;
      }
      return false;
    },

    toggle: function () {
      if (this.checkMedia()) {
        return;
      }

      this.adjustEarOffset(true);

      if (this.hasScroll()) {
        this.hasScrollLeft() ? this.showLeftEar() : this.hideLeftEar();
        this.hasScrollRight() ? this.showRightEar() : this.hideRightEar();
      } else {
        this.hideLeftEar();
        this.hideRightEar();
      }
    },
  };
}
