document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("[data-brodart-measurement]");
  const config = window.BrodartMeasurement;

  if (!form || !config) {
    return;
  }

  const widthInput = form.querySelector('[name="brodart_width"]');
  const heightInput = form.querySelector('[name="brodart_height"]');
  const accessoryInputs = form.querySelectorAll('[name="brodart_accessory"]');
  const piecesInputs = form.querySelectorAll('[name="brodart_pieces"]');
  const total = form.querySelector("[data-brodart-total]");
  const materialTotal = form.querySelector("[data-brodart-material]");
  const initialPricePerMeter = Number(config.pricePerMeter);
  let pricePerMeter = initialPricePerMeter;
  const formatter = new Intl.NumberFormat(config.locale || "ro-RO", {
    style: "currency",
    currency: config.currency || "RON",
    minimumFractionDigits: config.decimals,
    maximumFractionDigits: config.decimals,
  });

  const isValidMeasurement = (value, minimum, maximum) => {
    const steps = (value - minimum) / config.step;
    return (
      value >= minimum &&
      value <= maximum &&
      Math.abs(steps - Math.round(steps)) < 0.0001
    );
  };

  const updateTotal = () => {
    const width = Number(widthInput.value);
    const height = Number(heightInput.value);
    const pieces = Number(
      form.querySelector('[name="brodart_pieces"]:checked')?.value || 1,
    );
    const accessory = form.querySelector('[name="brodart_accessory"]:checked');
    const accessoryPrice = Number(accessory?.dataset.price || 0);
    const validWidth = isValidMeasurement(
      width,
      config.widthMin,
      config.widthMax,
    );
    const validHeight = isValidMeasurement(
      height,
      config.heightMin,
      config.heightMax,
    );

    if (!validWidth || !validHeight) {
      total.textContent = config.invalidText;
      return;
    }

    materialTotal.textContent = formatter.format(
      pricePerMeter * width * pieces,
    );
    total.textContent = formatter.format(
      (pricePerMeter + accessoryPrice) * width * pieces,
    );
  };

  widthInput.addEventListener("input", updateTotal);
  heightInput.addEventListener("input", updateTotal);
  accessoryInputs.forEach((input) =>
    input.addEventListener("change", updateTotal),
  );
  piecesInputs.forEach((input) =>
    input.addEventListener("change", updateTotal),
  );

  const variationForm = form.closest("form.variations_form");
  if (config.isVariable && variationForm && window.jQuery) {
    const selectFirstColorOption = () => {
      variationForm
        .querySelectorAll(
          'select[name="attribute_pa_culoare"], select[data-attribute_name="attribute_pa_culoare"]',
        )
        .forEach((select) => {
          if (select.value) {
            return;
          }

          const firstOption = Array.from(select.options).find(
            (option) => option.value,
          );
          if (firstOption) {
            window.jQuery(select).val(firstOption.value).trigger("change");
          }
        });
    };

    const syncColorSwatches = () => {
      variationForm
        .querySelectorAll(".brodart-color-swatches")
        .forEach((group) => {
          const select = group.parentElement.querySelector(
            "select[data-attribute_name]",
          );
          if (!select) {
            return;
          }

          group.querySelectorAll(".brodart-color-swatch").forEach((button) => {
            const selected = button.dataset.brodartColorValue === select.value;
            button.classList.toggle("is-selected", selected);
            button.setAttribute("aria-pressed", String(selected));
          });
        });
    };

    variationForm.addEventListener("click", (event) => {
      const button = event.target.closest(".brodart-color-swatch");
      if (!button || !variationForm.contains(button)) {
        return;
      }

      const group = button.closest(".brodart-color-swatches");
      const select = group?.parentElement.querySelector(
        "select[data-attribute_name]",
      );
      if (select) {
        window
          .jQuery(select)
          .val(button.dataset.brodartColorValue)
          .trigger("change");
      }
    });

    variationForm.addEventListener("change", (event) => {
      if (event.target.matches("select[data-attribute_name]")) {
        syncColorSwatches();
      }
    });

    window
      .jQuery(variationForm)
      .on("found_variation.brodartMeasurement", (_event, variation) => {
        pricePerMeter = Number(variation.display_price || 0);
        syncColorSwatches();
        updateTotal();
      })
      .on("reset_data.brodartMeasurement", () => {
        pricePerMeter = initialPricePerMeter;
        syncColorSwatches();
        updateTotal();
      });

    selectFirstColorOption();
  }

  updateTotal();
});
