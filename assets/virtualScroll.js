/*! virtualScroll v0.0.1 | (c) Justin Parra */
class virtScroll {
  constructor(divContainer, divTopSpacer, divItems, divHeight, elementHeight, numberOfElements, renderCallback) {
    this._divContainer = divContainer;
    this._containerHeight = $(this._divContainer).height();
    this._divTopSpacer = divTopSpacer;
    this._divItemsContainer = divItems;
    this._elementHeight = elementHeight;
    this._numberOfElements = numberOfElements;
    this._totalHeight = elementHeight * numberOfElements;
    this._oldTop = -1;
    this._renderCall = renderCallback;
    this._renderIsRunning = false;
    $(divHeight).height(numberOfElements * elementHeight);
    $(divItems).css("top", numberOfElements * elementHeight * -1);
    this._render;
    this._update = (newNumOfElements) => {
      this._numberOfElements = newNumOfElements;
      this._totalHeight = this._elementHeight * newNumOfElements;
      this._render(true);
    }

    const render = (input) => {
      if (typeof(input) != "undefined") {
        this._oldTop = -1;
      }
      if (this._renderIsRunning) {
        return;
      }
      this._renderIsRunning = true;

      const scrollTop = Math.floor(document.getElementById((this._divContainer).substring(1)).scrollTop);
      let startIndex = 0;
      let stopIndex = 0;
      let scrollTopHeight = 0;

      if (this._oldTop != scrollTop) {
        this._oldTop = scrollTop;

        if (this._oldTop <= 0) {
          $(divItems).css("top", this._numberOfElements * elementHeight * -1);
          startIndex = 0;
        } else {
          /**
           * the total number of elements that fit fully into the space above the view
           * also take away a few elements such that there is a little bit of a spacer when scrolling
           */
          const numberOfElementsThatFitIntoTopSpacer = Math.max(0, Math.floor(scrollTop / this._elementHeight) - 2);
          const height = numberOfElementsThatFitIntoTopSpacer * this._elementHeight;
          startIndex = numberOfElementsThatFitIntoTopSpacer;
          scrollTopHeight = Math.min(height, this._totalHeight - this._containerHeight);
          $(divItems).css("top", this._numberOfElements * elementHeight * -1 + scrollTopHeight);
        }

        const remainingElements = this._numberOfElements - startIndex;
        const remainingVisibleElements = Math.max(0, Math.min(remainingElements, Math.floor(this._containerHeight / this._elementHeight) + 5)); // 2 from before 3 after
        stopIndex = Math.min(this._numberOfElements, startIndex + Math.floor(this._containerHeight / this._elementHeight) + 3);
        this._renderCall(startIndex, stopIndex);

      }

      this._renderIsRunning = false;
    }

    document.getElementById((this._divContainer).substring(1)).addEventListener("scroll", render);
    render();

    this._render = render;
  }
}