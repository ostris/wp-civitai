import FileInfo from './FileInfo';
import ImageInfo from './ImageInfo';

export default interface ModelVersion {
  id: number;
  modelId: number;
  name: string;
  createdAt: string;
  updatedAt: string;
  trainedWords: string[];
  baseModel: string;
  earlyAccessTimeFrame: number;
  description: string;
  stats: {
    downloadCount: number;
    ratingCount: number;
    rating: number;
  };
  files: FileInfo[];
  images: ImageInfo[];
  downloadUrl: string;
}
