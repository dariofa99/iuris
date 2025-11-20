<script>

  const qrCodeExp = new QRCodeStyling({
    width: 200,
    height: 200,
    data: "{{url('expediente/encuestas/start')}}",
    dotsOptions: {
      color: "#000000",
      type: "rounded"
    },
    image: "{{ asset('dist/img/udenar-pdf.png') }}", // 👉 tu logo (mejor en PNG o SVG)
    imageOptions: {
     // crossOrigin: "anonymous", // Necesario si la imagen está en otro dominio
      margin: 2,               // Margen entre el QR y la imagen
      imageSize: 0.4         // Tamaño relativo (0.9 = 90% del QR)
    },
    backgroundOptions: {
      color: "#ffffff",
    },
   
  });

  qrCodeExp.append(document.getElementById("qr-code-exp"));

  const qrCodeConciliacion = new QRCodeStyling({
    width: 200,
    height: 200,
    data: "{{url('conciliacion/encuestas/start')}}",
    dotsOptions: {
      color: "#000000",
      type: "rounded"
    },
    backgroundOptions: {
      color: "#ffffff",
    },
    image: "{{ asset('dist/img/udenar-pdf.png') }}", // 👉 tu logo (mejor en PNG o SVG)
    imageOptions: {
     // crossOrigin: "anonymous", // Necesario si la imagen está en otro dominio
      margin: 2,               // Margen entre el QR y la imagen
      imageSize: 0.4            // Tamaño relativo (0.9 = 90% del QR)
    },
  });

  qrCodeConciliacion.append(document.getElementById("qr-code-conciliacion"));

</script>
