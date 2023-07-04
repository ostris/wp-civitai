const getComponentData = (componentName: string) => {
  if (componentName in window.__CIVITAI_DATA__) {
    return window.__CIVITAI_DATA__[componentName] ?? null;
  }
};

export default getComponentData;
