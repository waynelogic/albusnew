

export default function init($element) {
    const location = $element.dataset.location;

    console.log(location);
    oc.ajax('onChangeLocation', {
        data: { location: location  }
    })
}
