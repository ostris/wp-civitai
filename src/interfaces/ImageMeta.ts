export default interface ImageMeta {
  Size: string;
  seed: number;
  Model: string;
  steps: number;
  hashes: {
    model: string;
  };
  prompt: string;
  Version: string;
  sampler: string;
  cfgScale: number;
  resources: Array<{
    hash: string;
    name: string;
    type: string;
  }>;
  ModelHash: string;
  HiresUpscale: string;
  HiresUpscaler: string;
  negativePrompt: string;
  DenoisingStrength: string;
}
