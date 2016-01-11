//
//  EASYCryptclass.h
//  EASY vision 1.0
//
//  Created by wangHuiMing on 15/10/16.
//  Copyright © 2015年 Xuyang Gordon Wang. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface EASYCryptclass : NSObject
+(instancetype)initManager;
+ (NSString *)useDESEncryptString:(NSString *)str SetKey:(NSString *) sKey;
+ (NSString *)useDESDecryptString:(NSString *)str SetKey:(NSString *) sKey;

@end
