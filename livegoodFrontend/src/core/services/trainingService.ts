import api from "../api/client";

export const trainingService = {
  getCourses: (page = 1, limit = 20, filters?: any) =>
    api.get("/training/courses", { params: { page, limit, ...filters } }),

  getCourseDetails: (id: string) =>
    api.get(`/training/courses/${id}`),

  enrollCourse: (courseId: string) =>
    api.post(`/training/courses/${courseId}/enroll`),

  getProgress: (courseId: string) =>
    api.get(`/training/courses/${courseId}/progress`),

  updateProgress: (courseId: string, progress: number) =>
    api.put(`/training/courses/${courseId}/progress`, { progress }),

  getWebinars: (page = 1, limit = 20) =>
    api.get("/training/webinars", { params: { page, limit } }),

  getWebinarDetails: (id: string) =>
    api.get(`/training/webinars/${id}`),

  registerWebinar: (webinarId: string) =>
    api.post(`/training/webinars/${webinarId}/register`),

  getWebinarReplays: () =>
    api.get("/training/replays"),

  getMyEnrollments: () =>
    api.get("/training/my-enrollments"),

  getLessons: (courseId: string) =>
    api.get(`/training/courses/${courseId}/lessons`),

  getLessonDetails: (courseId: string, lessonId: string) =>
    api.get(`/training/courses/${courseId}/lessons/${lessonId}`),

  completeLession: (courseId: string, lessonId: string) =>
    api.post(`/training/courses/${courseId}/lessons/${lessonId}/complete`),
};
