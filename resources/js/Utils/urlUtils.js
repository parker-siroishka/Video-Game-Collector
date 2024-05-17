export const isValidUrl = async (url) => {
  try {
      new URL(url); // This will throw an error if the URL is invalid
  } catch (_) {
      return false; // If the URL is invalid, return false immediately
  }

  const img = new Image();
  img.src = url;
  return new Promise((resolve) => {
    img.onload = () => resolve(true);
    img.onerror = () => resolve(false);
  });
};
