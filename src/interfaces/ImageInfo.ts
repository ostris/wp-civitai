import ImageMeta from './ImageMeta';

export default interface ImageInfo {
  url: string;
  nsfw: string;
  width: number;
  height: number;
  hash: string;
  meta: ImageMeta | null;
}
